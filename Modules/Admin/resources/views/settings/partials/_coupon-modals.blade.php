@foreach($coupons ?? [] as $coupon)
    <!-- Delete Coupon Form (Hidden) -->
    <form id="deleteCoupon{{ $coupon->id }}" action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    <!-- Edit Coupon Modal -->
    <div class="modal fade" id="editCouponModal{{ $coupon->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil me-2 text-primary"></i> تعديل الكوبون
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.coupon_code') }}</label>
                                <input type="text" class="form-control rounded-3" name="code" value="{{ $coupon->code }}" style="text-transform: uppercase;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.coupon_name') }}</label>
                                <input type="text" class="form-control rounded-3" name="name" value="{{ $coupon->name }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('admin.discount_type') }}</label>
                                <select class="form-select rounded-3" name="type">
                                    <option value="percentage" {{ $coupon->type == 'percentage' ? 'selected' : '' }}>نسبة مئوية (%)</option>
                                    <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>مبلغ ثابت (ر.س)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('admin.discount_value') }}</label>
                                <input type="number" step="0.01" class="form-control rounded-3" name="value" value="{{ $coupon->value }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('admin.target_plan') }}</label>
                                <select class="form-select rounded-3" name="package_id">
                                    <option value="">{{ __('admin.all_plans') }}</option>
                                    @foreach($packages as $pkg)
                                        <option value="{{ $pkg->id }}" {{ $coupon->package_id == $pkg->id ? 'selected' : '' }}>{{ $pkg->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.start_date') }}</label>
                                <input type="date" class="form-control rounded-3" name="starts_at" value="{{ $coupon->starts_at?->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.end_date') }}</label>
                                <input type="date" class="form-control rounded-3" name="expires_at" value="{{ $coupon->expires_at?->format('Y-m-d') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.max_uses') }}</label>
                                <input type="number" class="form-control rounded-3" name="max_uses" value="{{ $coupon->max_uses }}">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check form-switch p-0 m-0">
                                    <input class="form-check-input premium-switch ms-0" type="checkbox" name="is_active" id="editCouponActive{{ $coupon->id }}" {{ $coupon->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold toggle-label ms-1" for="editCouponActive{{ $coupon->id }}">{{ __('admin.coupon_active') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-check-lg me-1"></i> {{ __('admin.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Add Coupon Modal -->
<div class="modal fade" id="addCouponModal" tabindex="-1" aria-labelledby="addCouponModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="addCouponModalLabel">
                    <i class="bi bi-ticket-perforated me-2 text-primary"></i> {{ __('admin.new_coupon') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('admin.coupon_code') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control rounded-3" name="code" placeholder="مثال: WELCOME20" style="text-transform: uppercase;">
                                <button type="button" class="btn btn-outline-secondary rounded-end-3" onclick="this.previousElementSibling.value = 'PROMO' + Math.random().toString(36).substring(2, 8).toUpperCase()">
                                    <i class="bi bi-dice-5"></i> {{ __('admin.generate') }}
                                </button>
                            </div>
                            <small class="text-muted">{{ __('admin.coupon_code_hint') }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('admin.coupon_name') }}</label>
                            <input type="text" class="form-control rounded-3" name="name" placeholder="مثال: خصم الترحيب">
                            <small class="text-muted">{{ __('admin.coupon_name_hint') }}</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('admin.discount_type') }}</label>
                            <select class="form-select rounded-3" name="type">
                                <option value="percentage">{{ __('admin.percentage') }}</option>
                                <option value="fixed">{{ __('admin.fixed_amount') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('admin.discount_value') }}</label>
                            <input type="number" step="0.01" class="form-control rounded-3" name="value" placeholder="20">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('admin.target_plan') }}</label>
                            <select class="form-select rounded-3" name="package_id">
                                <option value="">{{ __('admin.all_plans') }}</option>
                                @foreach($packages as $pkg)
                                    <option value="{{ $pkg->id }}">{{ $pkg->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('admin.start_date') }}</label>
                            <input type="date" class="form-control rounded-3" name="starts_at">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('admin.end_date') }}</label>
                            <input type="date" class="form-control rounded-3" name="expires_at">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('admin.max_uses') }}</label>
                            <input type="number" class="form-control rounded-3" name="max_uses" placeholder="{{ __('admin.max_uses_hint') }}">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch p-0 m-0">
                                <input class="form-check-input premium-switch ms-0" type="checkbox" name="is_active" id="couponActiveSwitch" checked>
                                <label class="form-check-label fw-bold toggle-label ms-1" for="couponActiveSwitch">{{ __('admin.coupon_active') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-check-lg me-1"></i> {{ __('admin.new_coupon') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($packages as $package)
    <form id="delete-package-{{ $package->id }}" action="{{ route('admin.settings.packages.destroy', $package->id) }}" method="POST" class="d-none">
        @csrf @method('DELETE')
    </form>
@endforeach

@foreach($features as $feature)
    <form id="delete-feature-{{ $feature->id }}" action="{{ route('admin.settings.features.destroy', $feature->id) }}" method="POST" class="d-none">
        @csrf @method('DELETE')
    </form>
@endforeach

