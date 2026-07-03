<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Package;
use Illuminate\Http\Request;

class CouponApiController extends Controller
{
    public function validateCoupon(Request $request)
    {
        $code = strtoupper($request->query('code'));
        $plan = $request->query('plan');

        if (! $code) {
            return response()->json(['valid' => false, 'message' => __('admin.enter_coupon_code')]);
        }

        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return response()->json(['valid' => false, 'message' => __('admin.invalid_coupon')]);
        }

        if (! $coupon->isValid()) {
            return response()->json(['valid' => false, 'message' => __('admin.expired_coupon')]);
        }

        // Check if coupon belongs to a specific package
        if ($coupon->package_id) {
            $package = Package::where('slug', $plan)->first();
            if (! $package || $coupon->package_id !== $package->id) {
                return response()->json(['valid' => false, 'message' => __('admin.coupon_not_for_plan')]);
            }
        }

        return response()->json([
            'valid' => true,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => (float) $coupon->value,
            'message' => __('admin.coupon_applied'),
            'discount_text' => $coupon->type === 'percentage'
                ? $coupon->value.'%'
                : $coupon->value.' '.\App\Models\SiteSetting::get('currency_symbol', 'جنيه'),
        ]);
    }
}
