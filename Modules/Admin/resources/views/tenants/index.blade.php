@extends('admin::layouts.hope-master')

@section('page-title', __('admin::admin.tenants.title'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">{{ __('admin::admin.tenants.title') }}</h2>
            <p class="text-muted mb-0">{{ __('admin::admin.tenants.subtitle') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.settings.index') }}#plans" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-patch-check me-2"></i> {{ __('admin::admin.subscriptions.plans_pricing') ?? 'الخطط والأسعار' }}
            </a>
            <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i> {{ __('admin::admin.tenants.add_new') }}
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-right: 4px solid #4361EE !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted x-small fw-bold text-uppercase mb-1">{{ __('admin::admin.tenants.stats.total') }}</div>
                            <div class="h3 fw-bold mb-0 text-dark">{{ $stats['total_count'] }}</div>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-building fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-right: 4px solid #10b981 !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted x-small fw-bold text-uppercase mb-1">الاشتراكات النشطة</div>
                            <div class="h3 fw-bold mb-0 text-success">{{ $stats['active_subscriptions'] }}</div>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-check-circle fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-right: 4px solid #f59e0b !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted x-small fw-bold text-uppercase mb-1">تنتهي قريباً</div>
                            <div class="h3 fw-bold mb-0 text-warning">{{ $stats['expiring_soon'] }}</div>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-hourglass-split fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #fff7ed 100%); border-right: 4px solid #4361EE !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted x-small fw-bold text-uppercase mb-1">{{ __('admin::admin.tenants.stats.students') }}</div>
                            <div class="h3 fw-bold mb-0 text-dark">{{ number_format($stats['total_students']) }}</div>
                        </div>
                        <div class="icon-box bg-primary text-white rounded-3 shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #4361EE 0%, #4895ef 100%);">
                            <i class="bi bi-mortarboard fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & List -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 p-4">
            <form action="{{ route('admin.tenants.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-0" placeholder="{{ __('admin::admin.tenants.filters.search_placeholder') }}" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="package_id" class="form-select bg-light border-0 x-small" onchange="this.form.submit()">
                        <option value="">كل الباقات</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>{{ $package->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="subscription_status" class="form-select bg-light border-0 x-small" onchange="this.form.submit()">
                        <option value="">كل الاشتراكات</option>
                        <option value="active" {{ request('subscription_status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="expired" {{ request('subscription_status') == 'expired' ? 'selected' : '' }}>منتهي</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select bg-light border-0 x-small" onchange="this.form.submit()">
                        <option value="">{{ __('admin::admin.tenants.filters.all_statuses') }}</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin::admin.tenants.filters.active') }}</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin::admin.tenants.filters.inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-dark rounded-pill w-100 x-small fw-bold">{{ __('admin::admin.tenants.filters.filter') }}</button>
                </div>
                @if(request()->anyFilled(['search', 'status', 'package_id', 'subscription_status']))
                    <div class="col-md-1">
                        <a href="{{ route('admin.tenants.index') }}" class="btn btn-outline-secondary rounded-pill w-100 x-small border-dashed">{{ __('admin::admin.tenants.filters.reset') }}</a>
                    </div>
                @endif
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-secondary small text-uppercase">
                            <th class="px-4 py-3 border-0">{{ __('admin::admin.tenants.table.center_admin') }}</th>
                            <th class="px-4 py-3 border-0">الاشتراك والفوترة</th>
                            <th class="px-4 py-3 border-0 text-center">الأداء والتفاعل</th>
                            <th class="px-4 py-3 border-0 text-center">{{ __('admin::admin.tenants.table.students') }}</th>
                            <th class="px-4 py-3 border-0 text-center">{{ __('admin::admin.tenants.table.status') }}</th>
                            <th class="px-4 py-3 border-0 text-end">{{ __('admin::admin.tenants.table.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($tenants as $tenant)
                            @php
                                $studentsCount = \App\Models\Student::where('tenant_id', $tenant->id)->count();
                                $admin = $tenant->users->first();
                                $statusClass = $tenant->status == 'active' ? 'success' : 'danger';
                                $statusLabel = $tenant->status == 'active' ? __('admin::admin.tenants.table.active') : __('admin::admin.tenants.table.inactive');
                                
                                $subscription = $tenant->currentSubscription;
                                $isExpired = $subscription && $subscription->ends_at && $subscription->ends_at->isPast();
                                $planName = $subscription && $subscription->package ? $subscription->package->name : ($subscription ? $subscription->type_label : 'بدون اشتراك');
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative me-3">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-weight: bold; font-size: 1.1rem; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                                @if($tenant->logo)
                                                    <img src="{{ asset('storage/' . $tenant->logo) }}" class="rounded-circle w-100 h-100 object-fit-contain p-1">
                                                @else
                                                    {{ substr($tenant->name, 0, 1) }}
                                                @endif
                                            </div>
                                            @if($tenant->status == 'active')
                                                <span class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle" style="width: 12px; height: 12px;"></span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="fw-bold text-dark text-decoration-none">
                                                    {{ $tenant->name }}
                                                </a>
                                                @if($tenant->type === 'instructor')
                                                    <span class="badge bg-info bg-opacity-10 text-info x-small rounded-pill" style="font-size: 0.65rem;">مدرس</span>
                                                @else
                                                    <span class="badge bg-purple bg-opacity-10 text-purple x-small rounded-pill" style="font-size: 0.65rem;">مركز</span>
                                                @endif
                                            </div>
                                            <span class="text-muted x-small">
                                                <i class="bi bi-person me-1"></i> {{ $admin->name ?? __('admin::admin.tenants.table.not_specified') }}
                                            </span>
                                            <div class="text-muted x-small mt-1">
                                                <i class="bi bi-link-45deg"></i> {{ $tenant->domain }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($subscription)
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span class="fw-bold text-dark small">{{ $planName }}</span>
                                                @if($isExpired)
                                                    <span class="badge bg-danger bg-opacity-10 text-danger x-small rounded-pill">منتهي</span>
                                                @else
                                                    <span class="badge bg-success bg-opacity-10 text-success x-small rounded-pill">نشط</span>
                                                @endif
                                            </div>
                                            <div class="text-muted x-small d-flex flex-column gap-1">
                                                <span>
                                                    <i class="bi bi-arrow-repeat me-1"></i>
                                                    @if($subscription->billing_cycle === 'yearly')
                                                        {{ __('admin::admin.subscriptions.yearly') ?? 'اشتراك سنوي' }}
                                                    @elseif($subscription->billing_cycle === 'term')
                                                        {{ __('admin::admin.subscriptions.term') ?? 'اشتراك ترم' }}
                                                    @else
                                                        {{ __('admin::admin.subscriptions.monthly') ?? 'اشتراك شهري' }}
                                                    @endif
                                                </span>
                                                <span>
                                                    <i class="bi bi-calendar2-event me-1"></i>
                                                    @if($subscription->ends_at)
                                                        {{ $isExpired ? 'انتهى في:' : 'ينتهي في:' }} {{ $subscription->ends_at->format('Y-m-d') }}
                                                    @else
                                                        اشتراك مستمر
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted small">لا يوجد اشتراك نشط</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="x-small text-muted mb-1">القيمة الكلية (LTV)</div>
                                            <span class="fw-bold text-success">{{ number_format($tenant->ltv ?: 0, 0) }} ج.م</span>
                                        </div>
                                        
                                        @if($subscription && $subscription->ends_at && !$isExpired)
                                            @php
                                                $totalDays = max(1, $subscription->created_at->diffInDays($subscription->ends_at));
                                                $remainingDays = now()->diffInDays($subscription->ends_at, false);
                                                $percent = min(100, max(0, ($remainingDays / $totalDays) * 100));
                                                $barColor = $percent < 20 ? 'danger' : ($percent < 50 ? 'warning' : 'primary');
                                            @endphp
                                            <div class="w-75">
                                                <div class="progress" style="height: 4px; background-color: rgba(0,0,0,0.05);">
                                                    <div class="progress-bar bg-{{ $barColor }}" role="progressbar" style="width: {{ 100 - $percent }}%"></div>
                                                </div>
                                                <div class="x-small text-muted mt-1" style="font-size: 0.65rem;">متبقي {{ (int)$remainingDays }} يوم</div>
                                            </div>
                                        @endif

                                        @if($tenant->last_activity_at)
                                            <div class="x-small text-muted mt-1 border-top pt-1 w-100">
                                                <i class="bi bi-lightning-charge text-warning"></i>
                                                {{ \Illuminate\Support\Carbon::parse($tenant->last_activity_at)->diffForHumans() }}
                                            </div>
                                        @else
                                            <div class="x-small text-muted mt-1 border-top pt-1 w-100">لا نشاط مؤخراً</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="h6 mb-0 fw-bold">{{ number_format($studentsCount) }}</span>
                                        <div class="text-muted small">{{ __('admin::admin.tenants.table.student_unit') }}</div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} rounded-pill px-3 py-2 border border-{{ $statusClass }} border-opacity-10">
                                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle shadow-none border dropdown-toggle-custom" type="button" onclick="toggleCustomDropdown(event, this)">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2" style="min-width: 200px;">
                                            <li><a class="dropdown-item rounded-3 mb-1" href="{{ route('admin.tenants.show', $tenant->id) }}"><i class="bi bi-eye me-2 text-primary"></i> {{ __('admin::admin.tenants.actions.view_details') }}</a></li>
                                            <li><a class="dropdown-item rounded-3 mb-1" href="{{ route('admin.tenants.edit', $tenant->id) }}"><i class="bi bi-pencil me-2 text-info"></i> {{ __('admin::admin.tenants.actions.edit_data') }}</a></li>
                                            @if($subscription)
                                                <li><a class="dropdown-item rounded-3 mb-1" href="{{ route('admin.subscriptions.edit', $subscription->id) }}"><i class="bi bi-card-checklist me-2 text-warning"></i> تعديل الاشتراك</a></li>
                                            @endif
                                            <li><a class="dropdown-item rounded-3 mb-1" href="{{ route('admin.tenants.impersonate', $tenant->id) }}"><i class="bi bi-box-arrow-in-right me-2 text-success"></i> {{ __('admin::admin.tenants.actions.impersonate') }}</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('admin::admin.tenants.actions.delete_confirm') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item rounded-3 text-danger">
                                                        <i class="bi bi-trash me-2"></i> {{ __('admin::admin.tenants.actions.delete') }}
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center">
                                    <div class="py-5">
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                                            <i class="bi bi-search fs-1 opacity-25"></i>
                                        </div>
                                        <h5 class="fw-bold text-dark">{{ __('admin::admin.tenants.no_results.title') }}</h5>
                                        <p class="text-muted">{{ __('admin::admin.tenants.no_results.description') }}</p>
                                        <a href="{{ route('admin.tenants.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">{{ __('admin::admin.tenants.no_results.view_all') }}</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($tenants->hasPages())
            <div class="card-footer bg-white border-0 p-4 pt-0">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>
@endsection
