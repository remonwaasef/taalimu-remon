@extends('admin::layouts.master')

@section('title', 'الرئيسية')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">لوحة القيادة</h2>
        <div class="text-muted">{{ date('Y-m-d') }}</div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary me-3">
                        <i class="bi bi-building fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">إجمالي المراكز</h6>
                        <h3 class="fw-bold mb-0">{{ $totalTenants }}</h3>
                    </div>
                    <a href="{{ route('admin.tenants.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success me-3">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">المراكز النشطة</h6>
                        <h3 class="fw-bold mb-0">{{ $activeTenants }}</h3>
                    </div>
                    <a href="{{ route('admin.tenants.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning me-3">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">اشتراكات تنتهي قريباً</h6>
                        <h3 class="fw-bold mb-0">{{ $expiringSoon }}</h3>
                    </div>
                    <a href="{{ route('admin.subscriptions.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info me-3">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">إجمالي الطلاب</h6>
                        <h3 class="fw-bold mb-0">{{ $totalStudents }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue and Support Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success me-3">
                        <i class="bi bi-cash-stack fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">إجمالي الإيرادات</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($totalRevenue, 2) }} <small class="fs-6 text-muted">ج.م</small></h3>
                    </div>
                    <!-- Assuming revenue details might be in subscriptions for now -->
                     <a href="{{ route('admin.subscriptions.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary me-3">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">إيرادات هذا الشهر</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($thisMonthRevenue, 2) }} <small class="fs-6 text-muted">ج.م</small></h3>
                    </div>
                     <a href="{{ route('admin.subscriptions.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning me-3">
                        <i class="bi bi-ticket-perforated fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">تذاكر مفتوحة</h6>
                        <h3 class="fw-bold mb-0">{{ $openTickets }}</h3>
                    </div>
                    <a href="{{ route('admin.tickets.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-secondary bg-opacity-10 p-3 rounded-circle text-secondary me-3">
                        <i class="bi bi-life-preserver fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">إجمالي التذاكر</h6>
                        <h3 class="fw-bold mb-0">{{ $totalTickets }}</h3>
                    </div>
                    <a href="{{ route('admin.tickets.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tenants -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">آخر المراكز المسجلة</h5>
            <a href="{{ route('admin.tenants.index') }}" class="btn btn-sm btn-light rounded-pill">عرض الكل</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">اسم المركز</th>
                        <th class="px-4 py-3 border-0">النطاق</th>
                        <th class="px-4 py-3 border-0">تاريخ التسجيل</th>
                        <th class="px-4 py-3 border-0">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Tenant::latest()->take(5)->get() as $tenant)
                        <tr>
                            <td class="px-4 position-relative">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        {{ substr($tenant->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="fw-bold text-decoration-none text-dark stretched-link">{{ $tenant->name }}</a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 text-muted">{{ $tenant->domain }}</td>
                            <td class="px-4 text-muted">{{ $tenant->created_at->format('Y-m-d') }}</td>
                            <td class="px-4">
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ $tenant->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">لا يوجد مراكز مسجلة بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Support Tickets -->
    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">آخر تذاكر الدعم</h5>
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-light rounded-pill">عرض الكل</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">الموضوع</th>
                        <th class="px-4 py-3 border-0">المستخدم</th>
                        <th class="px-4 py-3 border-0">المركز</th>
                        <th class="px-4 py-3 border-0">التاريخ</th>
                        <th class="px-4 py-3 border-0">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTickets as $ticket)
                        <tr>
                            <td class="px-4 fw-bold position-relative">
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-decoration-none text-dark stretched-link">{{ $ticket->subject }}</a>
                            </td>
                            <td class="px-4">{{ $ticket->user ? $ticket->user->name : 'غير محدد' }}</td>
                            <td class="px-4 text-muted">{{ $ticket->tenant ? $ticket->tenant->name : 'نظام' }}</td>
                            <td class="px-4 text-muted">{{ $ticket->created_at->format('Y-m-d') }}</td>
                            <td class="px-4">
                                @if($ticket->status == 'open')
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">مفتوحة</span>
                                @elseif($ticket->status == 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">قيد الانتظار</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">مغلقة</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">لا يوجد تذاكر دعم فني.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
