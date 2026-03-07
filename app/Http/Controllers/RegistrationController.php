<?php

namespace App\Http\Controllers;

use App\Services\TelegramService;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        // Bypassing cache to ensure data is fresh after seeder
        $packages = \App\Models\Package::with('features')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $packagesData = $packages->map(function($p) {
            $currency = \App\Models\SiteSetting::get('currency_symbol', 'جنيه');
            $discountPercent = 0;
            $savingsAmount = 0;
            if ($p->old_price > 0 && $p->old_price > $p->price) {
                $discountPercent = round((($p->old_price - $p->price) / $p->old_price) * 100);
                $savingsAmount = $p->old_price - $p->price;
            }

            return [
                'slug' => $p->slug,
                'name' => app()->getLocale() == 'ar' ? $p->name : ($p->name_en ?: $p->name),
                'price' => number_format($p->price, 0) . ' ' . $currency,
                'price_value' => number_format($p->price, 0),
                'price_raw' => (float)$p->price,
                'old_price' => $p->old_price > 0 ? number_format($p->old_price, 0) . ' ' . $currency : null,
                'old_price_value' => $p->old_price > 0 ? number_format($p->old_price, 0) : null,
                'old_price_raw' => (float)$p->old_price,
                'currency' => $currency,
                'discount_percent' => $discountPercent > 0 ? $discountPercent : null,
                'savings_amount' => $savingsAmount > 0 ? number_format($savingsAmount, 0) : null,
                'discount_label' => $p->discount_label,
                'yearly_price' => $p->yearly_price ? number_format($p->yearly_price, 0) . ' ' . $currency : number_format($p->price * 10, 0) . ' ' . $currency,
                'yearly_price_value' => $p->yearly_price ? number_format($p->yearly_price, 0) : number_format($p->price * 10, 0),
                'yearly_price_raw' => $p->yearly_price ?: ($p->price * 10),
                'regional_prices' => $p->regional_prices ?? [],
            ];
        })->values(); // Ensure it's a sequential array for Alpine.js

        return view('auth.register', compact('packages', 'packagesData'));
    }

    /**
     * Generate a unique subdomain from the center name
     */
    private function generateSubdomain($centerName)
    {
        // Arabic to English transliteration
        $transliteration = [
            'ا' => 'a', 'أ' => 'a', 'إ' => 'a', 'آ' => 'a',
            'ب' => 'b', 'ت' => 't', 'ث' => 'th',
            'ج' => 'j', 'ح' => 'h', 'خ' => 'kh',
            'د' => 'd', 'ذ' => 'dh', 'ر' => 'r',
            'ز' => 'z', 'س' => 's', 'ش' => 'sh',
            'ص' => 's', 'ض' => 'd', 'ط' => 't',
            'ظ' => 'z', 'ع' => 'a', 'غ' => 'gh',
            'ف' => 'f', 'ق' => 'q', 'ك' => 'k',
            'ل' => 'l', 'م' => 'm', 'ن' => 'n',
            'ه' => 'h', 'و' => 'w', 'ي' => 'y',
            ' ' => '-', '_' => '-'
        ];
        
        // Convert Arabic to English
        $slug = strtr($centerName, $transliteration);
        
        // Clean up: only letters, numbers, and hyphens
        $slug = preg_replace('/[^a-z0-9-]/', '', strtolower($slug));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Forbidden subdomains
        $forbidden = ['admin', 'www', 'api', 'app', 'dev', 'test', 'mail', 'webmail', 'portal', 'dashboard', 'edu'];
        if (in_array($slug, $forbidden)) {
            $slug = $slug . '-' . time();
        }

        // Fallback if empty
        if (empty($slug)) {
            $slug = 'center-' . time();
        }
        
        // Check for duplicates
        $originalSlug = $slug;
        $counter = 1;
        
        while (Tenant::where('domain', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    public function register(Request $request, TelegramService $telegram)
    {
        $request->validate([
            'center_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => [
                'required', 
                'string', 
                \Illuminate\Validation\Rules\Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'plan' => 'required|exists:packages,slug',
            'billing_cycle' => 'required|in:monthly,yearly',
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'country_code' => 'nullable|string|max:2',
            'payment_gateway' => 'required|in:stripe,paypal',
        ]);

        try {
            DB::beginTransaction();

            $coupon = null;
            $discountAmount = 0;
            $package = \App\Models\Package::where('slug', $request->plan)->first();
            
            $basePrice = ($request->billing_cycle === 'yearly') 
                ? ($package->yearly_price ?: ($package->price * 10)) 
                : $package->price;

            if ($request->has('coupon_code') && $request->coupon_code) {
                $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->first();
                if ($coupon && $coupon->isValid()) {
                    // Check if coupon belongs to a specific package
                    if ($coupon->package_id && $package && $coupon->package_id !== $package->id) {
                        $coupon = null; // Doesn't apply to this package
                    } else if ($package) {
                        // Calculate discount logic if needed for validation
                        $discountAmount = $coupon->calculateDiscount($basePrice);
                    }
                } else {
                    $coupon = null; // Invalid coupon
                }
            }

            // Auto-generate subdomain from center name
            $subdomain = $this->generateSubdomain($request->center_name);

            // 1. Create Tenant
            $tenant = Tenant::create([
                'name' => $request->center_name,
                'email' => $request->email,
                'phone' => $request->phone, 
                'domain' => $subdomain,
                'database_name' => 'edu_central', // Shared DB for now
                'status' => 'active', 
            ]);

            // 2. Create Admin User for this Tenant
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'locale' => session('locale', 'ar'),
            ]);
            $user->tenant_id = $tenant->id;
            $user->role = 'center_admin';
            $user->save();
            
            event(new Registered($user));
            
            // Set Spatie Team Context
            app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);

            // Assign Roles based on the selected package
            // Automatically assign 'Tutor' role if the package implies it's for a single tutor
            if (str_contains(strtolower($request->plan), 'tutor') || str_contains(strtolower($request->plan), 'teacher') || $request->plan === 'basic') {
                // Ensure the Tutor role exists for this tenant
                \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'tutor', 'guard_name' => 'web', 'tenant_id' => $tenant->id]);
                $user->assignRole('tutor');
            } else {
                $user->assignRole('center_admin');
            }

            // 3. Handle Subscription based on selected plan
            $stripePriceIds = [
                'basic' => config('services.stripe.price_basic'),
                'pro' => config('services.stripe.price_pro'),
            ];

            $stripePriceId = $stripePriceIds[$request->plan] ?? null;

            // Calculate discount amount for storage
            if ($coupon) {
                 // We need to know the price to calculate amount. 
                 // For now, let's just store the coupon details.
                 // Ideally we fetch price from package but simplifying for now.
                 $coupon->incrementUsage();
            }

            if ($request->plan === 'free-trial') {
                 \App\Models\Subscription::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'default',
                    'stripe_id' => 'sub_free_' . \Illuminate\Support\Str::random(10),
                    'stripe_status' => 'active',
                    'stripe_price' => 'price_free',
                    'quantity' => 1,
                    'ends_at' => $request->billing_cycle === 'yearly' ? now()->addYear() : now()->addDays(14),
                    'status' => 'active',
                    'billing_cycle' => $request->billing_cycle,
                    'coupon_id' => $coupon ? $coupon->id : null,
                    'coupon_code' => $coupon ? $coupon->code : null,
                    'base_price' => $basePrice,
                    'total_amount' => $basePrice,
                    'discount_amount' => 0,
                ]);

                // Send Telegram Notification to Admin (never send raw password)
                $telegram->sendRegistrationAlert($tenant, $user, '********');

                // Set session integrity token to prevent demo payment bypass
                session(['registration_hmac' => hash_hmac('sha256', $tenant->id . '|' . $user->id, config('app.key'))]);

                DB::commit();

                session([
                    'registration_success' => true,
                    'tenant_domain' => $subdomain,
                    'admin_email' => $request->email,
                    'center_name' => $request->center_name,
                    'billing_cycle' => $request->billing_cycle,
                    'base_price' => $basePrice,
                    'total_amount' => $basePrice,
                ]);

                return redirect()->route('registration.success');

            } else if ($request->payment_gateway === 'paypal') {
                // PayPal Paid Plan Flow (One-time Payment for Term)
                DB::commit();

                // Currency Detection for PayPal (Must be USD/EUR etc - no EGP)
                $countryCode = $request->input('country_code');
                $currency = 'USD';
                $amount = $package->regional_prices['default']['amount'] ?? 49;

                if ($request->billing_cycle === 'yearly') {
                    $amount = $package->regional_prices['default']['yearly_price'] ?? ($amount * 2);
                }

                if ($countryCode && isset($package->regional_prices[$countryCode])) {
                    $rPrice = $package->regional_prices[$countryCode];
                    $rCurrency = $rPrice['currency'] ?? 'USD';
                    
                    // PayPal supported currencies check (Simplified)
                    $supportedByPaypal = ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY'];
                    if (in_array(strtoupper($rCurrency), $supportedByPaypal)) {
                        $currency = $rCurrency;
                        $amount = $request->billing_cycle === 'yearly' ? ($rPrice['yearly_price'] ?? 0) : ($rPrice['amount'] ?? 0);
                    }
                }

                $paypal = app(\App\Services\PayPalService::class);
                $resp = $paypal->createOrder($amount, $currency, route('payment.paypal.success'), route('payment.cancel'));

                if ($resp && isset($resp['links'])) {
                    $approveLink = collect($resp['links'])->where('rel', 'approve')->first()['href'];
                    
                    // Create pending subscription record
                    \App\Models\Subscription::create([
                        'tenant_id' => $tenant->id,
                        'name' => 'default',
                        'paypal_id' => $resp['id'], // Store Order ID
                        'paypal_status' => 'CREATED',
                        'status' => 'trialing', // Pending payment
                        'gateway' => 'paypal',
                        'billing_cycle' => $request->billing_cycle,
                        'base_price' => $basePrice,
                        'total_amount' => ($basePrice - $discountAmount),
                        'discount_amount' => $discountAmount,
                        'ends_at' => null, // Will be set on success
                    ]);

                    return redirect()->away($approveLink);
                }

                return back()->withErrors(['error' => 'حدث خطأ أثناء الاتصال بـ PayPal.'])->withInput();

            } else {
                // Paid Plan Flow
                DB::commit();

                // Set session integrity token to prevent demo payment bypass
                session(['registration_hmac' => hash_hmac('sha256', $tenant->id . '|' . $user->id, config('app.key'))]);
                
                session([
                    'tenant_domain' => $subdomain,
                    'admin_email' => $request->email,
                    'center_name' => $request->center_name,
                    'tenant_id' => $tenant->id,
                    'selected_plan' => $request->plan,
                    'billing_cycle' => $request->billing_cycle,
                    'applied_coupon_id' => $coupon ? $coupon->id : null,
                    'applied_coupon_code' => $coupon ? $coupon->code : null,
                    'discount_amount' => $discountAmount,
                    'base_price' => $basePrice,
                    'total_amount' => $package ? ($basePrice - $discountAmount) : 0,
                ]);

                $isDemo = config('services.stripe.demo_mode') || 
                         (app()->environment(['local', 'testing']) && !$stripePriceId);

                if ($isDemo) {
                    return redirect()->route('payment.demo');
                }

                if (!$stripePriceId) {
                    \Log::warning("Stripe Price ID not found for plan: {$request->plan}. Falling back to demo mode for safety.");
                    return redirect()->route('payment.demo');
                }

                $tenant->createOrGetStripeCustomer([
                    'name' => $tenant->name,
                    'email' => $user->email,
                ]);

                // Regional Pricing Logic
                $countryCode = $request->input('country_code');
                // Fallback to server side detection if missing
                if (!$countryCode) {
                    try {
                        // Simple check, can be replaced with proper service
                        $ip = $request->ip();
                        // $countryCode = GeoIP::getLocation($ip)->iso_code; // If package was installed
                    } catch (\Exception $e) {}
                }

                $regionalPriceData = null;
                if ($countryCode && isset($package->regional_prices[$countryCode])) {
                    $rPrice = $package->regional_prices[$countryCode];
                    $amount = $request->billing_cycle === 'yearly' ? ($rPrice['yearly_price'] ?? 0) : ($rPrice['amount'] ?? 0);
                    $currency = $rPrice['currency'] ?? 'USD';
                    
                    if ($amount > 0) {
                        $regionalPriceData = [
                            'price_data' => [
                                'currency' => strtolower($currency),
                                'product_data' => [
                                    'name' => $package->name . ' (' . ucfirst($request->billing_cycle) . ')',
                                    'description' => "Subscription to {$package->name} plan",
                                ],
                                'unit_amount' => (int)($amount * 100), // Stripe expects cents
                                'recurring' => [
                                    'interval' => $request->billing_cycle === 'yearly' ? 'year' : 'month',
                                ],
                            ],
                            'quantity' => 1,
                        ];
                    }
                }
                
                // Note: Not passing coupon to Stripe here as it requires Stripe Coupon ID.
                $checkoutBuilder = $tenant->newSubscription('default', $regionalPriceData ? 'price_adhoc' : $stripePriceId);
                
                // If using ad-hoc price, we use checkout() with line_items override basically, 
                // but Laravel Cashier's newSubscription() expects a price ID usually. 
                // However, we can use checkout() directly with custom options.
                
                $checkoutOptions = [
                    'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('payment.cancel'),
                ];

                if ($regionalPriceData) {
                    // For ad-hoc price, we don't pass a price ID to newSubscription, or we pass a dummy and override in checkout.
                    // Actually, the cleanest way with Cashier for ad-hoc is to perform a Checkout Session manually or use ->checkout([ line_items => ... ])
                    // But ->newSubscription requires a price. 
                    
                    // Workaround: Use allowPromotionCodes = false if using adhoc to avoid conflicts or handle coupons manually.
                    // We will override line_items.
                    $checkoutOptions['line_items'] = [$regionalPriceData];
                }

                return $checkoutBuilder->checkout($checkoutOptions);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Registration error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Security fix: Generic error message instead of raw exception details
            $errorMessage = app()->getLocale() == 'ar' 
                ? 'حدث خطأ غير متوقع أثناء عملية التسجيل. يرجى المحاولة مرة أخرى لاحقاً أو التواصل مع الدعم الفني.'
                : 'An unexpected error occurred during registration. Please try again later or contact support.';

            return back()->withErrors(['error' => $errorMessage])->withInput();
        }
    }
}
