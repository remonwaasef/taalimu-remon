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

        $suggestedCurrency = session('suggested_currency', 'EGP');
        
        $packagesData = $packages->map(function($p) use ($suggestedCurrency) {
            $symbols = ['EGP' => 'EGP', 'USD' => '$', 'EUR' => '€'];
            if (app()->getLocale() == 'ar') {
                $symbols['EGP'] = 'ج.م';
            }
            $currency = $symbols[$suggestedCurrency] ?? $suggestedCurrency;
            $discountPercent = 0;
            $savingsAmount = 0;
            if ($p->old_price > 0 && $p->old_price > $p->price) {
                $discountPercent = round((($p->old_price - $p->price) / $p->old_price) * 100);
                $savingsAmount = $p->old_price - $p->price;
            }

            return [
                'slug' => $p->slug,
                'name' => match(app()->getLocale()) {
                    'ar' => $p->name,
                    'fr' => $p->name_fr ?: ($p->name_en ?: $p->name),
                    default => $p->name_en ?: $p->name,
                },
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
                'term_price' => $p->term_price ? number_format($p->term_price, 0) . ' ' . $currency : number_format($p->price * 4, 0) . ' ' . $currency,
                'term_price_value' => $p->term_price ? number_format($p->term_price, 0) : number_format($p->price * 4, 0),
                'term_price_raw' => $p->term_price ?: ($p->price * 4),
                'yearly_price' => $p->yearly_price ? number_format($p->yearly_price, 0) . ' ' . $currency : number_format($p->price * 10, 0) . ' ' . $currency,
                'yearly_price_value' => $p->yearly_price ? number_format($p->yearly_price, 0) : number_format($p->price * 10, 0),
                'yearly_price_raw' => $p->yearly_price ?: ($p->price * 10),
                'regional_prices' => $p->regional_prices ?? [],
                'trial_days' => (int)$p->trial_days,
                'features' => ($p->display_features && is_array($p->display_features) && count($p->display_features) > 0) 
                    ? $p->display_features 
                    : $p->features->map(function($f) {
                        $name = app()->getLocale() == 'ar' ? $f->name : ($f->name_en ?: $f->name);
                        $value = $f->pivot->value;
                        if ($value && !in_array(strtolower($value), ['true', '1', 'yes'])) {
                            return (app()->getLocale() == 'ar' ? ($name . ': ' . $value) : ($name . ': ' . $value));
                        }
                        return $name;
                    })->toArray(),
            ];
        })->values();

        $accountType = request('account_type');
        if (!in_array($accountType, ['center', 'instructor'])) {
            $accountType = null;
        }

        return view('auth.register', compact('packages', 'packagesData', 'accountType'));
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

    public function register(Request $request, TelegramService $telegram, \App\Services\GeoIPService $geoIP)
    {
        $request->validate([
            'account_type' => 'required|in:center,instructor',
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
            'billing_cycle' => 'required|in:monthly,term,yearly',
            'currency' => 'nullable|in:EGP,USD,EUR',
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'country_code' => 'nullable|string|max:2',
            'payment_gateway' => 'required|in:paypal,paymob,test',
        ]);

        $currency = $request->input('currency', 'EGP');

        // VPN/LOCATION SECURITY: Prevent EGP usage outside Egypt
        if ($currency === 'EGP') {
            $detectedCountry = $geoIP->getCountryCode($request->ip());
            if ($detectedCountry && $detectedCountry !== 'EG') {
                $errorMsg = app()->getLocale() == 'ar' 
                    ? 'عذراً، الدفع بالجنيه المصري متاح فقط للمقيمين داخل مصر. يرجى اختيار عملة أخرى (USD أو EUR).'
                    : 'EGP pricing is only available for users in Egypt. Please select another currency (USD or EUR).';
                return back()->withErrors(['currency' => $errorMsg])->withInput();
            }
        }

        // Enforce gateway based on currency
        if ($currency === 'EGP' && $request->payment_gateway === 'paypal') {
            return back()->withErrors(['payment_gateway' => __('PayPal does not support EGP. Please use Paymob.')])->withInput();
        }
        if ($currency !== 'EGP' && $request->payment_gateway === 'paymob') {
            return back()->withErrors(['payment_gateway' => __('Paymob is only available for EGP payments.')])->withInput();
        }

        try {
            DB::beginTransaction();

            $coupon = null;
            $discountAmount = 0;
            $package = \App\Models\Package::where('slug', $request->plan)->first();
            $currency = $request->input('currency', 'EGP');
            $regionalPrice = $package->getRegionalPrice($currency);
            
            if ($request->billing_cycle === 'term') {
                $basePrice = $regionalPrice['term_price'] ?? ($regionalPrice['amount'] * 4);
            } elseif ($request->billing_cycle === 'yearly') {
                $basePrice = $regionalPrice['yearly_price'] ?? ($regionalPrice['amount'] * 10);
            } else {
                $basePrice = $regionalPrice['amount'];
            }

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
                'type' => $request->account_type,
                'database_name' => 'edu_central', // Shared DB for now
                'status' => 'active', 
            ]);

            // Save locale and currency into tenant settings from session/GeoIP
            $registrationLocale = session('locale', 'ar');
            $registrationCurrency = session('suggested_currency', $currency);
            $tenant->settings = array_merge($tenant->settings ?? [], [
                'default_locale' => $registrationLocale,
                'currency' => $registrationCurrency,
            ]);
            $tenant->save();

            // 2. Create Admin User for this Tenant
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'locale' => session('locale', 'ar'),
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]);
            $user->tenant_id = $tenant->id;
            $user->role = $request->account_type === 'instructor' ? 'instructor' : 'center_admin';
            $user->save();
            
            // 2.1 Create Instructor Profile if applicable
            // For 'instructor' account type, this is mandatory.
            // For 'center' account type, we create a primary instructor profile for the owner by default.
            \App\Models\Instructor::create([
                'tenant_id' => $tenant->id,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'status' => 'active',
            ]);
            
            event(new Registered($user));
            
            // Send Onboarding Email 1 (Welcome) if enabled
            if (env('ENABLE_ONBOARDING_EMAILS', false)) {
                try {
                    \Illuminate\Support\Facades\Mail::to($user->email)->send(
                        new \App\Mail\TenantOnboardingMail($tenant, $user, 1)
                    );
                } catch (\Exception $e) {
                    \Log::error('Failed to send onboarding email 1: ' . $e->getMessage());
                }
            }
            
            // Set Spatie Team Context
            app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
            $user->assignRole($user->role);

                // 3. Handle Subscription logic
                $finalAmount = $basePrice - $discountAmount;

                if ($package->trial_days > 0) {
                    \App\Models\Subscription::create([
                        'tenant_id' => $tenant->id,
                        'package_id' => $package->id,
                        'name' => 'default',
                        'stripe_id' => 'sub_trial_' . \Illuminate\Support\Str::random(10),
                        'stripe_status' => 'trialing',
                        'stripe_price' => $package->slug,
                        'quantity' => 1,
                        'trial_ends_at' => now()->addDays($package->trial_days),
                        'ends_at' => now()->addDays($package->trial_days),
                        'status' => 'trialing',
                        'billing_cycle' => $request->billing_cycle,
                        'base_price' => $basePrice,
                        'total_amount' => $finalAmount,
                        'discount_amount' => $discountAmount,
                    ]);

                    $telegram->sendRegistrationAlert($tenant, $user, '(Registration - Free Trial)');

                    DB::commit();

                    // Set session integrity token to prevent payment bypass
                    session(['registration_hmac' => hash_hmac('sha256', $tenant->id . '|' . $user->id, config('app.key'))]);

                    session([
                        'registration_success' => true,
                        'tenant_domain' => $subdomain,
                        'admin_email' => $request->email,
                        'center_name' => $request->center_name,
                        'tenant_id' => $tenant->id,
                        'selected_plan' => $request->plan,
                        'billing_cycle' => $request->billing_cycle,
                        'selected_currency' => $currency,
                        'applied_coupon_id' => $coupon ? $coupon->id : null,
                        'applied_coupon_code' => $coupon ? $coupon->code : null,
                        'discount_amount' => $discountAmount,
                        'base_price' => $basePrice,
                        'total_amount' => $finalAmount,
                    ]);

                    return redirect()->route('registration.success');
                }

                // Paid Plan Flow (Modular Payment Gateway)
                DB::commit();

                // Set session integrity token to prevent payment bypass
                session(['registration_hmac' => hash_hmac('sha256', $tenant->id . '|' . $user->id, config('app.key'))]);
                
                session([
                    'tenant_domain' => $subdomain,
                    'admin_email' => $request->email,
                    'center_name' => $request->center_name,
                    'tenant_id' => $tenant->id,
                    'selected_plan' => $request->plan,
                    'billing_cycle' => $request->billing_cycle,
                    'selected_currency' => $currency,
                    'applied_coupon_id' => $coupon ? $coupon->id : null,
                    'applied_coupon_code' => $coupon ? $coupon->code : null,
                    'discount_amount' => $discountAmount,
                    'base_price' => $basePrice,
                    'total_amount' => $finalAmount,
                ]);

                // Modular Payment Gateway Logic
                $gateway = \App\Services\PaymentFactory::make($request->payment_gateway);
                
                $redirectUrl = $gateway->createCheckoutSession($tenant, $package, $request->billing_cycle, [
                    'coupon_id' => $coupon ? $coupon->id : null,
                    'coupon_code' => $coupon ? $coupon->code : null,
                    'discount_amount' => $discountAmount,
                    'base_price' => $basePrice,
                    'total_amount' => $finalAmount,
                ]);

                return redirect()->away($redirectUrl);

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
