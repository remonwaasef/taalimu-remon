<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Package;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Services\TelegramService;
use Illuminate\Support\Str;

class TenantRegistrationService
{
    protected TelegramService $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * Register a new tenant and its initial admin user.
     *
     * @param array $data Expected keys: center_name, email, phone, account_type, plan, billing_cycle, currency, coupon_code
     * @param string|null $password Raw password (will be hashed). Null if social auth.
     * @param string|null $googleId Google ID if social auth.
     * @param \Stevebauman\Location\Position|null $geoData GeoIP data for location context
     * @return array [ 'tenant' => Tenant, 'user' => User ]
     */
    public function registerTenant(array $data, ?string $password = null, ?string $googleId = null, $geoData = null): array
    {
        $tenant = null;
        $user = null;

        DB::transaction(function () use ($data, $password, $googleId, $geoData, &$tenant, &$user) {
            $package = Package::where('slug', $data['plan'])->first();
            $currency = $data['currency'] ?? 'EGP';
            $regionalPrice = $package->getRegionalPrice($currency);
            
            $basePrice = $regionalPrice['amount'];
            if ($data['billing_cycle'] === 'term') {
                $basePrice = $regionalPrice['term_price'] ?? ($regionalPrice['amount'] * 4);
            } elseif ($data['billing_cycle'] === 'yearly') {
                $basePrice = $regionalPrice['yearly_price'] ?? ($regionalPrice['amount'] * 10);
            }

            $coupon = null;
            $discountAmount = 0;
            if (!empty($data['coupon_code'])) {
                $coupon = Coupon::where('code', $data['coupon_code'])->first();
                if ($coupon && $coupon->isValid()) {
                    if ($coupon->package_id && $package && $coupon->package_id !== $package->id) {
                        $coupon = null;
                    } else if ($package) {
                        $discountAmount = $coupon->calculateDiscount($basePrice);
                    }
                } else {
                    $coupon = null;
                }
            }

            // Generate Subdomain
            $subdomain = self::generateSubdomain($data['center_name']);

            // 1. Create Tenant
            $tenant = Tenant::forceCreate([
                'name' => $data['center_name'],
                'email' => $data['email'],
                'phone' => $data['phone'], 
                'domain' => $subdomain,
                'type' => $data['account_type'],
                'database_name' => 'edu_central',
                'status' => 'active', 
            ]);

            // Save locale and currency
            $settings = $tenant->settings ?? [];
            $settings['locale'] = session('locale', 'ar');
            $settings['currency'] = session('suggested_currency', $currency);
            if ($geoData) {
                $settings['registration_location'] = [
                    'country' => $geoData->countryName ?? null,
                    'country_code' => $geoData->countryCode ?? null,
                    'city' => $geoData->cityName ?? null,
                    'ip' => $geoData->ip ?? null,
                ];
            }
            $tenant->settings = $settings;
            $tenant->save();

            // 2. Create Admin User
            $userData = [
                'name' => $data['center_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'role' => 'center_admin',
                'tenant_id' => $tenant->id,
            ];

            if ($password) {
                $userData['password'] = Hash::make($password);
            }
            if ($googleId) {
                $userData['google_id'] = $googleId;
                $userData['email_verified_at'] = now();
            }

            $user = User::create($userData);

            // 2.1 Create Instructor Profile
            \App\Models\Instructor::create([
                'tenant_id' => $tenant->id,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'status' => 'active',
            ]);

            // 3. Subscription & Billing
            $finalPrice = max(0, $basePrice - $discountAmount);
            $isTrialPlan = $package && $package->trial_days > 0;

            if ($isTrialPlan) {
                $tenant->active_subscription_object = \App\Models\Subscription::forceCreate([
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
                    'billing_cycle' => $data['billing_cycle'],
                    'base_price' => $basePrice,
                    'total_amount' => $finalPrice,
                    'discount_amount' => $discountAmount,
                ]);

                // Record applied coupon if exists
                if ($coupon) {
                    $coupon->increment('used_count');
                }
            }

            // Expose values on tenant object for controllers
            $tenant->temp_coupon = $coupon;
            $tenant->temp_discount_amount = $discountAmount;
            $tenant->temp_base_price = $basePrice;
            $tenant->temp_total_amount = $finalPrice;

            // 4. Default System Data
            $this->seedTenantData($tenant);
        });

        // Event & Notification
        if (!$googleId) {
            event(new Registered($user));
        }

        // Notification failure must never roll back or block a successful registration
        try {
            $this->telegram->sendRegistrationAlert($tenant, $user, '********');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Registration Telegram alert failed: ' . $e->getMessage());
        }

        return ['tenant' => $tenant, 'user' => $user];
    }

    /**
     * Generate a unique subdomain from the center name
     */
    public static function generateSubdomain($centerName)
    {
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
            'ة' => 'h', 'ى' => 'a', 'ئ' => 'e', 'ء' => 'a', 'ؤ' => 'o',
            ' ' => '-', '_' => '-'
        ];

        // Convert Arabic to English
        $slug = strtr($centerName, $transliteration);
        
        // Clean up: only letters, numbers, and hyphens
        $slug = preg_replace('/[^a-z0-9-]/', '', strtolower($slug));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Forbidden subdomains
        $forbidden = ['admin', 'www', 'api', 'app', 'dev', 'test', 'mail', 'webmail', 'portal', 'dashboard', 'edu', 'cdn', 'localhost'];
        if (in_array($slug, $forbidden)) {
            $slug = $slug . '-' . time();
        }

        // Fallback if empty
        if (empty($slug)) {
            $slug = 'center-' . time();
        }

        $originalSlug = $slug;
        $counter = 1;

        while (Tenant::where('domain', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Seed initial tenant data.
     */
    protected function seedTenantData(Tenant $tenant)
    {
        // 1. Roles & Permissions Setup for the Tenant
        \Database\Seeders\RolesAndPermissionsSeeder::seedForTenant($tenant->id);

        // 2. Assign Center Admin Role to the first user.
        // run() above resets the team id to null, so re-scope the assignment
        // to this tenant to match how IdentifyTenant resolves roles at runtime.
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
        $admin = \App\Models\User::where('tenant_id', $tenant->id)->first();
        if ($admin) {
            $admin->assignRole('center_admin');
        }

        // Note: per-tenant payment gateway config seeding was removed — the
        // `payment_gateway_configs` table has no migration and is never read.
    }
}
