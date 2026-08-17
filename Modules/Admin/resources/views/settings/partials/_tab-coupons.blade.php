                    <!-- Coupons Tab -->
                    <div class="tab-pane fade" id="coupons" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-1">{{ __('admin.coupons_management') }}</h5>
                                <p class="text-muted small mb-0">{{ __('admin.coupons_note') }}</p>
                            </div>
                            <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addCouponModal">
                                <i class="bi bi-plus-lg me-2"></i> {{ __('admin.new_coupon') }}
                            </button>
                        </div>

                        <!-- Coupons Table -->
                        <div class="table-responsive" data-mobile-cards>
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="rounded-start-3">{{ __('admin.coupon_code') }}</th>
                                        <th>{{ __('admin.coupon_name') }}</th>
                                        <th>{{ __('admin.discount_value') }}</th>
                                        <th>{{ __('admin.target_plan') }}</th>
                                        <th>{{ __('admin.valid_until') }}</th>
                                        <th>{{ __('admin.max_uses') }}</th>
                                        <th>{{ __('admin.active_status') }}</th>
                                        <th class="rounded-end-3 text-center">{{ __('admin.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($coupons ?? [] as $coupon)
                                    <tr>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded-2 fw-bold">{{ $coupon->code }}</code>
                                        </td>
                                        <td>{{ $coupon->name ?? '-' }}</td>
                                        <td>
                                            @if($coupon->type == 'percentage')
                                                <span class="badge bg-success-subtle text-success rounded-pill">{{ $coupon->value }}%</span>
                                            @else
                                                <span class="badge bg-primary-subtle text-primary rounded-pill">{{ number_format($coupon->value, 0) }} ر.س</span>
                                            @endif
                                        </td>
                                        <td>{{ $coupon->package ? $coupon->package->name : __('admin.all_plans') }}</td>
                                        <td>
                                            @if($coupon->expires_at)
                                                @if($coupon->expires_at->isPast())
                                                    <span class="text-danger small"><i class="bi bi-x-circle me-1"></i>{{ __('admin.expired') }}</span>
                                                @else
                                                    <span class="text-muted small">{{ __('admin.valid_until') }} {{ $coupon->expires_at->format('Y-m-d') }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted small">{{ __('admin.unlimited_uses') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark rounded-pill">
                                                {{ $coupon->used_count }} / {{ $coupon->max_uses ?? '∞' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($coupon->is_active && $coupon->isValid())
                                                <span class="badge bg-success rounded-pill">{{ __('admin.active_status') }}</span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill">{{ __('admin.inactive_status') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary rounded-start-pill" data-bs-toggle="modal" data-bs-target="#editCouponModal{{ $coupon->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger rounded-end-pill" data-confirm-delete data-form="deleteCoupon{{ $coupon->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-ticket-perforated fs-1 mb-3 d-block opacity-50"></i>
                                                <p class="mb-0">{{ __('admin.no_coupons') }}</p>
                                                <small>{{ __('admin.no_coupons_hint') }}</small>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
