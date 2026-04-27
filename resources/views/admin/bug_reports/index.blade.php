@extends('admin::layouts.master')

@section('title', 'تقارير البلاغات (Beta Bugs)')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-1">🐛 بلاغات المنصة (Beta)</h2>
        <div>
            <a href="{{ route('admin.bug_reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-sync-alt me-1"></i> تحديث
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <h6 class="text-muted mb-1">إجمالي البلاغات</h6>
                    <h2 class="fw-bold text-dark mb-0">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger">
                <div class="card-body">
                    <h6 class="text-muted mb-1">مفتوحة (تحتاج مراجعة)</h6>
                    <h2 class="fw-bold text-danger mb-0">{{ $stats['open'] }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning">
                <div class="card-body">
                    <h6 class="text-muted mb-1">قيد المعالجة</h6>
                    <h2 class="fw-bold text-warning mb-0">{{ $stats['in_progress'] }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body">
                    <h6 class="text-muted mb-1">تم الحل</h6>
                    <h2 class="fw-bold text-success mb-0">{{ $stats['resolved'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form action="{{ route('admin.bug_reports.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted">حالة البلاغ</label>
                    <select name="status" class="form-select">
                        <option value="">الكل</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>مفتوح</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>تم الحل</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلق</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">الأولوية</label>
                    <select name="priority" class="form-select">
                        <option value="">الكل</option>
                        <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>حرجة جداً</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>عالية</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>متوسطة</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>منخفضة</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">تصفية</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3 border-0">رقم البلاغ</th>
                            <th class="border-0">العنوان والوصف</th>
                            <th class="border-0">المُبلغ والمركز</th>
                            <th class="border-0">النوع والأولوية</th>
                            <th class="border-0">الحالة</th>
                            <th class="border-0 text-end px-4">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td class="px-4 text-muted fw-bold">#{{ $report->id }}</td>
                            <td style="max-width: 300px;">
                                <div class="fw-bold text-dark text-truncate" title="{{ $report->title }}">{{ $report->title }}</div>
                                <div class="text-muted small text-truncate" title="{{ $report->description }}">{{ $report->description }}</div>
                                @if($report->page_url)
                                    <a href="{{ $report->page_url }}" target="_blank" class="small text-primary"><i class="fas fa-external-link-alt me-1"></i>الرابط</a>
                                @endif
                                @if($report->screenshot)
                                    <a href="{{ asset($report->screenshot) }}" target="_blank" class="small text-info ms-2"><i class="fas fa-image me-1"></i>صورة</a>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $report->tenant->name ?? 'غير معروف' }}</div>
                                <div class="small text-muted">{{ $report->user->name ?? 'غير معروف' }} ({{ $report->user->email ?? '' }})</div>
                            </td>
                            <td>
                                <div>{{ $report->category_label }}</div>
                                <span class="badge bg-{{ $report->priority_badge }} mt-1">أولوية {{ $report->priority }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $report->status_badge }}">
                                    @if($report->status == 'open') مفتوح
                                    @elseif($report->status == 'in_progress') قيد المعالجة
                                    @elseif($report->status == 'resolved') تم الحل
                                    @else مغلق
                                    @endif
                                </span>
                                <div class="small text-muted mt-1" dir="ltr">{{ $report->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="text-end px-4">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $report->id }}">
                                    إدارة
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3 opacity-50"></i>
                                    <h5 class="fw-bold">المنصة مستقرة!</h5>
                                    <p class="mb-0">لا توجد بلاغات مسجلة حالياً.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($reports->hasPages())
                <div class="p-3 border-top">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals must be outside the table to render correctly -->
@foreach($reports as $report)
<div class="modal fade" id="editModal{{ $report->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="{{ route('admin.bug_reports.status', $report->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">إدارة بلاغ #{{ $report->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <h5 class="fw-bold text-primary">{{ $report->title }}</h5>
                            <p class="text-muted" style="white-space: pre-wrap;">{{ $report->description }}</p>
                            
                            @if($report->page_url)
                                <div class="mb-3">
                                    <span class="fw-bold small">رابط الصفحة:</span>
                                    <a href="{{ $report->page_url }}" target="_blank" class="d-block small mt-1" style="word-break: break-all;">{{ $report->page_url }}</a>
                                </div>
                            @endif

                            @if($report->screenshot)
                                <div class="mb-3">
                                    <span class="fw-bold small">صورة الشاشة المرفقة:</span>
                                    <a href="{{ asset($report->screenshot) }}" target="_blank" class="d-block mt-1">
                                        <img src="{{ asset($report->screenshot) }}" class="img-fluid rounded border shadow-sm" style="max-height: 150px;">
                                    </a>
                                </div>
                            @endif

                            @if($report->browser_info)
                                <hr>
                                <h6 class="fw-bold text-muted small mb-2"><i class="fas fa-laptop-code me-1"></i> معلومات المتصفح والنظام:</h6>
                                <div class="bg-light rounded border p-3" dir="ltr" style="max-height: 250px; overflow-y: auto;">
                                    <pre class="mb-0 small" style="white-space: pre-wrap; word-break: break-word; text-align: left; color: #e83e8c;"><code>{{ json_encode($report->browser_info, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-5">
                            <div class="bg-light border rounded p-3 h-100">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-dark"><i class="fas fa-tasks me-1"></i> تحديث الحالة</label>
                                    <select name="status" class="form-select shadow-sm">
                                        <option value="open" {{ $report->status == 'open' ? 'selected' : '' }}>مفتوح (Open)</option>
                                        <option value="in_progress" {{ $report->status == 'in_progress' ? 'selected' : '' }}>قيد المعالجة (In Progress)</option>
                                        <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>تم الحل (Resolved)</option>
                                        <option value="closed" {{ $report->status == 'closed' ? 'selected' : '' }}>مغلق (Closed)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-dark"><i class="fas fa-sticky-note me-1"></i> ملاحظات الإدارة (للمطور فقط)</label>
                                    <textarea name="admin_notes" class="form-control shadow-sm" rows="6" placeholder="اكتب ملاحظاتك هنا لحفظ ما تم إصلاحه...">{{ $report->admin_notes }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
