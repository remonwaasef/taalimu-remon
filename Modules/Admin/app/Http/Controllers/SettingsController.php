<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SiteSetting;
use App\Models\Package;
use App\Models\Coupon;

class SettingsController extends Controller
{
    // Constructor removed: Middleware handled in routes
    public function index()
    {
        // Authorization check handled by middleware
        
        $packages = Package::with('features')->orderBy('sort_order')->get();
        // Get features ordered by sort_order
        $features = \App\Models\Feature::orderBy('sort_order')->get();
        $coupons = Coupon::with('package')->latest()->paginate(10);
        
        return view('admin::settings.index', compact('packages', 'features', 'coupons'));
    }

    public function update(Request $request)
    {
        // Authorization check handled by middleware
        
        $request->validate([
            'site_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'packages.*.name' => 'required|string|max:255',
            'packages.*.price' => 'nullable|numeric|min:0',
            'packages.*.yearly_price' => 'nullable|numeric|min:0',
            'packages.*.old_price' => 'nullable|numeric|min:0',
        ]);

        $settings = [
            'site_name',
            'admin_email',
            'site_description_ar',
            'site_description_en',
            'site_description_fr',
            'primary_color',
            'secondary_color',
            'session_lifetime',
            'max_login_attempts',
            'currency_symbol',
            'currency_code',
        ];

        // Explicitly handle boolean/checkbox settings
        $booleans = [
            'default_dark_mode',
            'enable_2fa',
        ];

        foreach ($settings as $setting) {
            if ($request->has($setting)) {
                SiteSetting::set($setting, $request->input($setting));
            }
        }

        foreach ($booleans as $boolean) {
            SiteSetting::set($boolean, $request->has($boolean));
        }

        // Handle Packages update
        if ($request->has('packages')) {
            foreach ($request->input('packages') as $id => $data) {
                $package = Package::find($id);
                if ($package) {
                    if (isset($data['display_features'])) {
                        $data['display_features'] = array_values(array_filter(array_map('trim', explode("\n", $data['display_features']))));
                    }

                    $data['is_active'] = isset($data['is_active']);
                    $data['is_featured'] = isset($data['is_featured']);
                    $data['is_default'] = isset($data['is_default']);

                    if ($data['is_default']) {
                        Package::where('id', '!=', $id)->update(['is_default' => false]);
                    }

                    // START: Sync Base Price to Default Regional Price
                    // This ensures that the valid base price is always available as the "default" smart price fallback
                    $defaultCurrency = \App\Models\SiteSetting::get('currency_code', 'USD');
                    
                    // Fetch current regional prices to avoid losing data not in the request
                    $currentRegional = $package->regional_prices ?? [];
                    
                    $basePriceData = [
                        'amount' => $data['price'] ?? 0,
                        'yearly_price' => $data['yearly_price'] ?? 0,
                        'old_price' => $data['old_price'] ?? 0,
                        'currency' => $defaultCurrency,
                        'discount_label' => $data['regional_prices']['default']['discount_label'] ?? ($currentRegional['default']['discount_label'] ?? null) 
                    ];

                    // Safely merge: prioritize request data for regional prices, but ensure 'default' is synced
                    $mergedRegional = array_merge($currentRegional, $data['regional_prices'] ?? []);
                    $mergedRegional['default'] = array_merge($mergedRegional['default'] ?? [], $basePriceData);
                    
                    $data['regional_prices'] = $mergedRegional;
                    // END: Sync Base Price

                    $package->update($data);

                    if (isset($data['limits'])) {
                        $syncData = [];
                        foreach ($data['limits'] as $featureId => $value) {
                            $syncData[$featureId] = ['value' => $value];
                        }
                        $package->features()->sync($syncData);
                    }
                }
            }
        }

        // Clear Caches
        \Illuminate\Support\Facades\Cache::forget('landing_packages');
        \Illuminate\Support\Facades\Cache::forget('landing_features');
        \Illuminate\Support\Facades\Cache::forget('site_settings');

        // Clear Tenancy Caches to reflect package changes immediately
        try {
            if (extension_loaded('redis') && class_exists('Redis')) {
                $redis = \Illuminate\Support\Facades\Redis::connection();
                $keys = $redis->keys('taalimu:tenancy:domain:*');
                if (!empty($keys)) {
                    foreach ($keys as $key) {
                        // Redis::keys() might return prefixed keys depending on configuration
                        $redis->del($key);
                    }
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to clear tenancy caches: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'تم حفظ جميع التعديلات بنجاح');
    }

    public function storePackage(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'slug' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'duration_in_days' => 'nullable|integer|min:1',
        ]);

        Package::create($data);
        
        \Illuminate\Support\Facades\Cache::forget('landing_packages');
        return redirect()->back()->with('success', 'تم إضافة الباقة بنجاح');
    }

    public function destroyPackage($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();
        
        \Illuminate\Support\Facades\Cache::forget('landing_packages');
        return redirect()->back()->with('success', 'تم حذف الباقة بنجاح');
    }

    public function storeFeature(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'code' => 'nullable|string',
            'type' => 'nullable|in:limit,boolean',
            'category' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        \App\Models\Feature::create($data);
        
        \Illuminate\Support\Facades\Cache::forget('landing_features');
        return redirect()->back()->with('success', 'تم إضافة الميزة بنجاح');
    }

    public function updateFeature(Request $request, $id)
    {
        $feature = \App\Models\Feature::findOrFail($id);
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'category' => 'nullable|string',
            'is_visible' => 'nullable|boolean',
        ]);

        $feature->update($data);
        
        \Illuminate\Support\Facades\Cache::forget('landing_features');
        return redirect()->back()->with('success', 'تم تحديث الميزة بنجاح');
    }

    public function destroyFeature($id)
    {
        $feature = \App\Models\Feature::findOrFail($id);
        $feature->delete();
        
        \Illuminate\Support\Facades\Cache::forget('landing_features');
        return redirect()->back()->with('success', 'تم حذف الميزة بنجاح');
    }
}
