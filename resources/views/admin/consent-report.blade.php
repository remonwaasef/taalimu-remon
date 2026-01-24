@extends('admin::layouts.master')

@section('title', 'تقارير الكوكيز')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📊 تقارير موافقات ملفات تعريف الارتباط</h2>
        <a href="{{ route('consent.export') }}" class="btn btn-primary">
            <i class="bi bi-download"></i> تصدير CSV
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">إجمالي الموافقات</h6>
                    <h2 class="fw-bold text-primary mb-0">{{ $stats['total_consents'] }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">وافق على التحليلات</h6>
                    <h2 class="fw-bold text-success mb-0">{{ $stats['analytics_accepted'] }}</h2>
                    <small class="text-muted">
                        {{ $stats['total_consents'] > 0 ? round(($stats['analytics_accepted'] / $stats['total_consents']) * 100) : 0 }}% من المجموع
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">وافق على التسويق</h6>
                    <h2 class="fw-bold text-info mb-0">{{ $stats['marketing_accepted'] }}</h2>
                    <small class="text-muted">
                        {{ $stats['total_consents'] > 0 ? round(($stats['marketing_accepted'] / $stats['total_consents']) * 100) : 0 }}% من المجموع
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">موافقات اليوم</h6>
                    <h2 class="fw-bold text-warning mb-0">{{ $stats['today_consents'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Consents Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">آخر 50 موافقة</h5>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-end">ID</th>
                            <th class="text-end">المستخدم</th>
                            <th class="text-end">عنوان IP</th>
                            <th class="text-center">تحليلات</th>
                            <th class="text-center">تسويق</th>
                            <th class="text-end">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_consents as $consent)
                        <tr>
                            <td class="text-end text-muted">#{{ $consent->id }}</td>
                            <td class="text-end">
                                @if($consent->user_id)
                                    <span class="badge bg-primary">مستخدم #{{ $consent->user_id }}</span>
                                @else
                                    <span class="badge bg-secondary">زائر</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <code class="text-muted">{{ $consent->ip_address }}</code>
                            </td>
                            <td class="text-center">
                                @if($consent->analytics_consent)
                                    <span class="badge bg-success">✓ نعم</span>
                                @else
                                    <span class="badge bg-danger">✗ لا</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($consent->marketing_consent)
                                    <span class="badge bg-success">✓ نعم</span>
                                @else
                                    <span class="badge bg-danger">✗ لا</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div>{{ \Carbon\Carbon::parse($consent->created_at)->format('Y-m-d H:i') }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($consent->created_at)->diffForHumans() }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    <h5>لا توجد موافقات بعد</h5>
                                    <p class="mb-0">عندما يوافق الزوار على الكوكيز، ستظهر بياناتهم هنا</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
