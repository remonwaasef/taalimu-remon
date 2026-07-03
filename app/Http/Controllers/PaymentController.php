<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Tenant;
use App\Services\PaymentProcessingService;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * PaymentController — يعالج نتائج الدفع (Callbacks/Redirects) من جميع البوابات.
 * المنطق المشترك مفوّض لـ PaymentProcessingService لتطبيق مبدأ DRY.
 */
class PaymentController extends Controller
{
    public function __construct(
        protected PaymentProcessingService $paymentService
    ) {}

    /**
     * Stripe Success (Deprecated — redirects to home).
     */
    public function success(Request $request, TelegramService $telegram)
    {
        return redirect()->route('home')->withErrors(['error' => 'Stripe is no longer supported. Please use Paymob.']);
    }

    /**
     * PayPal Success Callback.
     */
    public function paypalSuccess(Request $request, \App\Services\PayPalService $paypal, TelegramService $telegram)
    {
        $orderId = $request->get('token');

        if ($orderId) {
            $details = $paypal->captureOrder($orderId);

            if ($details && $details['status'] === 'COMPLETED') {
                session(['registration_success' => true]);

                $tenantId = session('tenant_id');
                $tenant = $tenantId ? Tenant::find($tenantId) : null;

                if ($tenant) {
                    $planSlug = session('selected_plan', 'pro');
                    $package = Package::where('slug', $planSlug)->first();
                    $billingCycle = session('billing_cycle', 'monthly');

                    $this->paymentService->activateSubscription(
                        $tenant, 'paypal', $orderId, $package, $billingCycle,
                        session('base_price', 0),
                        session('total_amount', 0),
                        ['paypal_id' => $orderId, 'paypal_status' => 'COMPLETED']
                    );

                    $this->paymentService->loginAdminAndNotify($tenant, $telegram, '******** (PayPal Order)');
                }

                if (session('is_subscription_change')) {
                    session()->forget('is_subscription_change');

                    return redirect()->route('center.subscription.success', ['tenant' => $tenant->domain]);
                }

                return redirect()->route('dashboard');
            }
        }

        return redirect()->route('home')->withErrors(['error' => __('payment.paypal_failed')]);
    }

    /**
     * Paymob Redirect Callback.
     */
    public function paymobCallback(Request $request, TelegramService $telegram)
    {
        /** @var \App\Services\PaymentGateways\PaymobGateway $gateway */
        $gateway = \App\Services\PaymentFactory::make('paymob');

        // 1. Verify Redirect HMAC
        $isHmacValid = $gateway->verifyRedirectHmac($request->all());
        if (! $isHmacValid) {
            Log::warning('Paymob Redirect HMAC Mismatch', [
                'received_hmac' => $request->get('hmac'),
                'payload' => $request->except(['hmac']),
            ]);
            abort(403, 'Invalid Payment Signature');
        }

        $success = filter_var($request->get('success'), FILTER_VALIDATE_BOOLEAN);
        $transactionId = $request->get('id');
        $merchantOrderId = $request->get('merchant_order_id') ?? $request->get('order');

        Log::info('Paymob Redirect Received', [
            'success' => $success, 'transaction_id' => $transactionId,
            'merchant_order_id' => $merchantOrderId, 'hmac_valid' => $isHmacValid,
        ]);

        if ($success && $transactionId) {
            // 2. Context Restoration (Prioritize DB Source of Truth to prevent Session Fixation)
            $restored = $this->paymentService->restoreContextFromMerchantOrder($merchantOrderId);
            if ($restored) {
                $tenantId = $restored['tenant_id'];
                $planSlug = $restored['plan_slug'];
                $billingCycle = $restored['billing_cycle'];
                $isChange = $restored['is_change'];
                $basePrice = $restored['base_price'] ?? session('base_price', 0);
                $totalAmount = $restored['total_amount'] ?? session('total_amount', 0);
            } else {
                // Fallback to session only if DB restore fails, but validate tenant to prevent session tampering
                $tenantId = session('tenant_id');
                $planSlug = session('selected_plan');
                $billingCycle = session('billing_cycle', 'monthly');
                $isChange = session('is_subscription_change', false);
                $basePrice = session('base_price', 0);
                $totalAmount = session('total_amount', 0);
            }

            $tenant = $tenantId ? Tenant::find($tenantId) : null;

            // Fallback: find tenant via existing subscription
            if (! $tenant) {
                $existingSub = \App\Models\Subscription::where('stripe_id', 'sub_paymob_'.$transactionId)->first();
                if ($existingSub) {
                    $tenant = $existingSub->tenant;
                    $isChange = true;
                    Log::info("Paymob Context Restored via subscription for Trans ID: {$transactionId}");
                }
            }

            // Robust upgrade detection
            if ($tenant && ! $isChange) {
                $isChange = $tenant->subscriptions()->where('name', 'default')->exists();
            }

            if ($tenant) {
                $package = Package::where('slug', $planSlug)->first();

                // Fallback pricing from package if session was lost
                if ($totalAmount <= 0 && $package) {
                    $totalAmount = match ($billingCycle) {
                        'yearly' => $package->yearly_price,
                        'term' => $package->term_price,
                        default => $package->price,
                    };
                    $basePrice = $totalAmount;
                }

                $this->paymentService->activateSubscription(
                    $tenant, 'paymob', $transactionId, $package, $billingCycle,
                    $basePrice, $totalAmount
                );

                $this->paymentService->loginAdminAndNotify($tenant, $telegram, "******** (Paymob ID: {$transactionId})");

                session(['registration_success' => true]);

                if ($isChange) {
                    return redirect()->route('center.subscription.success', ['tenant' => $tenant->domain]);
                }

                return redirect()->route('dashboard');
            }

            Log::error('Paymob Payment Success but Tenant not found', [
                'transaction_id' => $transactionId, 'merchant_order_id' => $merchantOrderId,
            ]);

            return redirect()->route('home')->withErrors(['error' => __('payment.success_but_tenant_not_found')]);
        }

        // Payment failed or was cancelled
        return $this->handlePaymobFailure($request, $merchantOrderId);
    }

    /**
     * معالجة فشل/إلغاء دفع Paymob.
     */
    private function handlePaymobFailure(Request $request, ?string $merchantOrderId)
    {
        $tenantId = session('tenant_id');
        $isChange = session('is_subscription_change', false);

        if (! $tenantId && $merchantOrderId) {
            $restored = $this->paymentService->restoreContextFromMerchantOrder($merchantOrderId);
            if ($restored) {
                $tenantId = $restored['tenant_id'];
                $isChange = $restored['is_change'];
            }
        }

        Log::warning('Paymob Payment Failed/Cancelled', [
            'tenant_id' => $tenantId, 'is_change' => $isChange,
            'all_params' => $request->all(),
        ]);

        if ($isChange && $tenantId) {
            $tenant = Tenant::find($tenantId);
            if ($tenant) {
                return redirect()->route('center.subscription.index', ['tenant' => $tenant->domain])
                    ->with('error', __('payment.paymob_failed'));
            }
        }

        return redirect()->route('home')->withErrors(['error' => __('payment.paymob_failed')]);
    }

    /**
     * Payment Cancel page.
     */
    public function cancel()
    {
        return view('auth.payment-cancel');
    }

    /**
     * Demo Payment page (for testing).
     */
    public function demo()
    {
        $planSlug = session('selected_plan');
        $tenantId = session('tenant_id');

        if (! $planSlug || ! $tenantId) {
            return redirect()->route('register')->withErrors(['error' => __('Your session has expired. Please register again.')]);
        }

        $package = Package::where('slug', $planSlug)->first();
        $tenant = Tenant::find($tenantId);

        if (! $package || ! $tenant) {
            return redirect()->route('register')->withErrors(['error' => __('Selected plan or tenant not found.')]);
        }

        $couponCode = session('applied_coupon_code');
        $discountAmount = session('discount_amount', 0);
        $basePrice = session('base_price', $package->price);
        $totalAmount = session('total_amount', $package->price);
        $billingCycle = session('billing_cycle', 'monthly');

        return view('auth.payment-demo', compact('package', 'tenant', 'couponCode', 'discountAmount', 'totalAmount', 'basePrice', 'billingCycle'));
    }

    /**
     * Demo Payment Success (simulated).
     */
    public function demoSuccess(TelegramService $telegram)
    {
        if (! session('tenant_id')) {
            return redirect()->route('register');
        }

        // Security: Validate session integrity
        $userId = auth()->check() ? auth()->id() : \App\Models\User::where('tenant_id', session('tenant_id'))->where('role', 'center_admin')->value('id');
        $expectedHmac = hash_hmac('sha256', session('tenant_id').'|'.$userId, config('app.key'));
        if (! hash_equals($expectedHmac, session('registration_hmac', ''))) {
            Log::warning('Demo payment bypass attempt detected', ['ip' => request()->ip()]);

            return redirect()->route('register')->withErrors(['error' => __('payment.invalid_session')]);
        }

        $tenant = Tenant::find(session('tenant_id'));
        if (! $tenant) {
            return redirect()->route('register');
        }

        $planSlug = session('selected_plan', 'pro');
        $package = Package::where('slug', $planSlug)->orWhere('id', $planSlug)->first();
        $billingCycle = session('billing_cycle', 'monthly');

        $this->paymentService->activateSubscription(
            $tenant, 'demo', '', $package, $billingCycle,
            session('base_price', 0),
            session('total_amount', 0),
            [
                'coupon_id' => session('applied_coupon_id'),
                'coupon_code' => session('applied_coupon_code'),
                'discount_amount' => session('discount_amount', 0),
            ]
        );

        // Process coupon usage
        $this->paymentService->processCoupon(
            session('applied_coupon_id'),
            $tenant,
            session('discount_amount', 0)
        );

        // Handle subscription upgrade redirect
        if (session('is_subscription_change')) {
            session()->forget('is_subscription_change');
            $this->sendUpgradeNotification($tenant);

            return redirect()->route('center.subscription.success', ['tenant' => $tenant->domain]);
        }

        session(['registration_success' => true]);
        $this->paymentService->loginAdminAndNotify($tenant, $telegram, '********');

        return redirect()->route('dashboard');
    }

    /**
     * إرسال إشعار ترقية الاشتراك عبر Telegram.
     */
    private function sendUpgradeNotification(Tenant $tenant): void
    {
        try {
            $user = Auth::user();
            $userName = $user ? $user->name : __('payment.unknown_user');

            $sub = \App\Models\Subscription::where('tenant_id', $tenant->id)->latest()->first();
            $packageName = $sub ? $sub->type_label : __('payment.unspecified');
            $endsAt = ($sub && $sub->ends_at) ? $sub->ends_at->format('Y-m-d') : __('payment.unspecified');
            $amount = session('total_amount', 0).' '.\App\Models\SiteSetting::get('currency_symbol', 'جنيه');

            $msg = '<b>🔄 '.__('payment.subscription_upgrade')."</b>\n\n";
            $msg .= '<b>🏢 '.__('payment.center').":</b> {$tenant->name}\n";
            $msg .= '<b>👤 '.__('payment.user').":</b> {$userName}\n";
            $msg .= '<b>📦 '.__('payment.new_package').":</b> {$packageName}\n";
            $msg .= '<b>💰 '.__('payment.amount_paid').":</b> {$amount}\n";
            $msg .= '<b>⏳ '.__('payment.new_expiry').":</b> {$endsAt}\n\n";
            $msg .= '#SubscriptionUpgrade';

            app(TelegramService::class)->sendAdminNotification($msg);
        } catch (\Throwable $e) {
            Log::error('Failed to send upgrade notification: '.$e->getMessage());
        }
    }
}
