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
            'billing_cycle' => 'required|in:monthly,yearly',
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'country_code' => 'nullable|string|max:2',
            'payment_gateway' => 'required|in:paypal,paymob,test',
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
                'type' => $request->account_type,
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
            
            // Set Spatie Team Context
            app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
            $user->assignRole($user->role);

                // 3. Handle Subscription based on selected plan (Dynamic Gateway)
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
                    'applied_coupon_id' => $coupon ? $coupon->id : null,
                    'applied_coupon_code' => $coupon ? $coupon->code : null,
                    'discount_amount' => $discountAmount,
                    'base_price' => $basePrice,
                    'total_amount' => ($basePrice - $discountAmount),
                ]);

                // Modular Payment Gateway Logic
                $gateway = \App\Services\PaymentFactory::make($request->payment_gateway);
                
                $redirectUrl = $gateway->createCheckoutSession($tenant, $package, $request->billing_cycle, [
                    'coupon_id' => $coupon ? $coupon->id : null,
                    'coupon_code' => $coupon ? $coupon->code : null,
                    'discount_amount' => $discountAmount,
                    'total_amount' => ($basePrice - $discountAmount),
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
