<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function store(Request $request)
    {
        // Authorization: Only super admins can manage coupons
        if (!auth()->user()->hasRole('super_admin')) {
             abort(403, 'Unauthorized action.');
        }

        \Log::info('Coupon store request:', $request->all());

        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'name' => 'nullable|string|max:100',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'package_id' => 'nullable|exists:packages,id',
            'starts_at' => 'nullable|date|after_or_equal:today',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'max_uses' => 'nullable|integer|min:1',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');
        $validated['package_id'] = $validated['package_id'] ?: null;

        Coupon::create($validated);

        return redirect()->back()->with('success', 'تم إنشاء الكوبون بنجاح!');
    }

    public function update(Request $request, Coupon $coupon)
    {
        // Authorization
        if (!auth()->user()->hasRole('super_admin')) {
             abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'name' => 'nullable|string|max:100',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'package_id' => 'nullable|exists:packages,id',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'max_uses' => 'nullable|integer|min:1',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');
        $validated['package_id'] = $validated['package_id'] ?: null;

        $coupon->update($validated);

        return redirect()->back()->with('success', 'تم تحديث الكوبون بنجاح!');
    }

    public function destroy(Coupon $coupon)
    {
        // Authorization
        if (!auth()->user()->hasRole('super_admin')) {
             abort(403, 'Unauthorized action.');
        }

        $coupon->delete();

        return redirect()->back()->with('success', 'تم حذف الكوبون بنجاح!');
    }
}
