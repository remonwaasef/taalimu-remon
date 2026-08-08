<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Services\PaymentGateways\PaymobGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Public self-service invoice payment (no login required).
 * The shareable link is a Laravel signed URL, so only centers can mint it,
 * and `sales.payment_token` ties each Paymob order back to its invoice.
 */
class OnlinePaymentController extends Controller
{
    protected $gateway;

    public function __construct(PaymobGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Mint (or reuse) a payment token for an invoice.
     */
    protected function ensurePaymentToken(Sale $sale): string
    {
        if (! $sale->payment_token) {
            $sale->forceFill(['payment_token' => Str::random(16)])->save();
        }

        return $sale->payment_token;
    }

    /**
     * Public invoice page (signed URL).
     */
    public function show(Request $request, Sale $sale)
    {
        // Tenant isolation: signed URL already encodes the sale id; still make sure the
        // sale belongs to the host tenant before rendering any data.
        if (! app()->bound('tenant') || (int) $sale->tenant_id !== (int) app('tenant')->id) {
            abort(404);
        }

        if (! app('tenant')->hasFeature('online_payments')) {
            abort(403, 'الدفع الإلكتروني غير متاح في باقتك الحالية.');
        }

        $sale->load(['student', 'items']);

$remaining = $sale->total_amount - $sale->paid_amount;

        if ($remaining <= 0) {
            return redirect()->route('center.pay.result', ['sale' => $sale, 'status' => 'paid']);
        }

        $this->ensurePaymentToken($sale);

        $checkoutUrl = \Illuminate\Support\Facades\URL::signedRoute('center.pay.submit', [
            'sale' => $sale->id,
            'tenant' => app('tenant')->domain,
        ]);

        return view('center::pay-payment.payment', compact('sale', 'remaining', 'checkoutUrl'));
    }

    /**
     * POST from the pay page — kicks off Paymob iframe.
     */
    public function pay(Request $request, Sale $sale)
    {
        if (! app()->bound('tenant') || (int) $sale->tenant_id !== (int) app('tenant')->id) {
            abort(403);
        }

        if (! app('tenant')->hasFeature('online_payments')) {
            abort(403, 'الدفع الإلكتروني غير متاح في باقتك الحالية.');
        }

        $remaining = $sale->total_amount - $sale->paid_amount;

        if ($remaining <= 0) {
            return redirect()->route('center.pay.result', ['sale' => $sale, 'status' => 'paid']);
        }

        try {
            $token = $this->ensurePaymentToken($sale);
            $iframeUrl = $this->gateway->createSaleCheckout($sale, $token, $remaining);

            return redirect()->away($iframeUrl);
        } catch (\Exception $e) {
            Log::error('Online payment checkout failed', [
                'sale_id' => $sale->id,
                'tenant_id' => $sale->tenant_id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'تعذر إنشاء رابط الدفع حالياً، يرجى المحاولة لاحقاً أو التواصل مع المركز.');
        }
    }

    /**
     * Public feedback page after Paymob redirect (info only; accounting happens in webhook).
     * status: success | failed | paid
     */
    public function result(Request $request, Sale $sale, string $status)
    {
        if (app()->bound('tenant') && (int) $sale->tenant_id !== (int) app('tenant')->id) {
            abort(403);
        }

        $sale->load('student');

        return view('center::pay-payment.result', compact('sale', 'status'));
    }

    /**
     * Shareable signed pay link for an invoice (used by center dashboard + parent portal).
     */
    public static function payLink(Sale $sale): string
    {
        $tenant = $sale->relationLoaded('tenant') ? $sale->tenant : $sale->tenant()->first();

        return \Illuminate\Support\Facades\URL::signedRoute('center.pay.show', [
            'sale' => $sale->id,
            'tenant' => $tenant?->domain,
        ]);
    }
}