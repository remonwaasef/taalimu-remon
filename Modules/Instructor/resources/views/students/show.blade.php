@extends('instructor::components.layouts.master')

@section('page-title', 'ملف الطالب')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Student Info Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        @if($student->user && $student->user->qr_identifier)
                            <div class="d-flex flex-column align-items-center">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($student->user->qr_identifier) }}" alt="QR Code" class="img-fluid rounded-4 shadow-sm mb-2" style="max-width: 150px;">
                                <span class="badge bg-light text-dark border user-select-all fs-6 font-monospace">{{ $student->user->qr_identifier }}</span>
                            </div>
                        @else
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">
                                <i class="fas fa-user-graduate fa-3x"></i>
                            </div>
                        @endif
                    </div>
                    <h3 class="fw-bold mb-1">{{ $student->name }}</h3>
                    <p class="text-muted mb-4">{{ $student->user->email ?? $student->email }}</p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        @php
                            $portalUrl = route('student.portal', $student->user->qr_identifier ?? 'invalid');
                            $shareMsg = "مرحباً {$student->name}، يمكنك متابعة حضورك وحساباتك عبر رابط بوابتك التعليمية: {$portalUrl}";
                            // Format phone: remove any non-digits, and if starts with 0, replace with 20
                            $cleanPhone = preg_replace('/[^0-9]/', '', $student->phone);
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '20' . substr($cleanPhone, 1);
                            }
                        @endphp
                        <a href="https://api.whatsapp.com/send?phone={{ $cleanPhone }}&text={{ urlencode($shareMsg) }}" target="_blank" class="btn btn-success rounded-pill px-4">
                            <i class="fab fa-whatsapp me-2"></i> بوابة الطالب
                        </a>
                        <button onclick="copyPortalLink('{{ $portalUrl }}')" class="btn btn-light rounded-pill px-3" title="نسخ رابط البوابة">
                            <i class="fas fa-link"></i>
                        </button>
                        <a href="tel:{{ $student->phone }}" class="btn btn-light rounded-pill px-3">
                            <i class="fas fa-phone"></i>
                        </a>
                    </div>

                    <hr class="opacity-10 my-4">

                    <div class="text-start">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted small">تاريخ الانضمام:</span>
                            <span class="fw-bold small">{{ $student->created_at->format('Y/m/d') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted small">رقم الهاتف:</span>
                            <span class="fw-bold small" dir="ltr">{{ $student->phone }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-0">
                            <span class="text-muted small">الحالة:</span>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">نشط</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-8">
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 text-white" style="background: var(--primary-gradient);">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="bg-white bg-opacity-20 p-2 rounded-3">
                                    <i class="fas fa-calendar-check fa-lg"></i>
                                </div>
                                <span class="badge bg-white bg-opacity-20 rounded-pill">نسبة الحضور</span>
                            </div>
                            @php
                                $attendanceTotal = $attendances->count();
                                $attendanceRate = $attendanceTotal > 0 ? round(($attendances->where('status', 'present')->count() / $attendanceTotal) * 100) : 0;
                            @endphp
                            <h2 class="fw-bold mb-0">{{ $attendanceRate }}%</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 bg-success text-white">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="bg-white bg-opacity-20 p-2 rounded-3">
                                    <i class="fas fa-money-bill-wave fa-lg"></i>
                                </div>
                                <span class="badge bg-white bg-opacity-20 rounded-pill">إجمالي المدفوع</span>
                            </div>
                            <h2 class="fw-bold mb-0">{{ number_format($student->sales->sum('paid_amount'), 2) }} <small class="fs-6">ج.م</small></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    @php
                        $totalDue = $student->enrollments->sum(fn($e) => $e->course->price ?? 0);
                        $totalPaid = $student->sales->sum('paid_amount');
                        $balance = $totalDue - $totalPaid;
                    @endphp
                    <div class="card border-0 shadow-sm rounded-4 text-white {{ $balance > 0 ? 'bg-danger' : 'bg-dark' }}">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="bg-white bg-opacity-20 p-2 rounded-3">
                                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                                </div>
                                <span class="badge bg-white bg-opacity-20 rounded-pill">المتبقي</span>
                            </div>
                            <h2 class="fw-bold mb-0">{{ number_format($balance, 2) }} <small class="fs-6">ج.م</small></h2>
                        </div>
                    </div>
                </div>
            </div>

            @if($balance > 0)
                <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4">
                    <div class="d-flex align-items-center justify-content-between p-2">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">تذكير بسداد المصروفات</h6>
                            <p class="text-muted small mb-0">الطالب لديه مديونية متبقية قدرها {{ number_format($balance, 2) }} ج.م</p>
                        </div>
                        @php
                            $msg = "تحية طيبة، نود تذكيركم بأن الطالب {$student->name} لديه مديونية متبقية قدرها " . number_format($balance, 2) . " ج.م لمجموعات المدرس " . (auth()->user()->name ?? 'المعلم') . ". يرجى السداد في أقرب وقت. شكراً لكم.";
                            $cleanPhone = preg_replace('/[^0-9]/', '', $student->phone);
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '20' . substr($cleanPhone, 1);
                            }
                        @endphp
                        <a href="https://api.whatsapp.com/send?phone={{ $cleanPhone }}&text={{ urlencode($msg) }}" target="_blank" class="btn btn-warning rounded-pill px-4 fw-bold">
                            <i class="fab fa-whatsapp me-2"></i> إرسال تذكير
                        </a>
                    </div>
                </div>
            @endif

            <!-- Tabs for Details -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 p-0">
                    <ul class="nav nav-tabs nav-fill border-0" id="studentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active border-0 py-3 fw-bold" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance-panel" type="button" role="tab">سجل الحضور</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link border-0 py-3 fw-bold" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments-panel" type="button" role="tab">سجل المدفوعات</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content" id="studentTabsContent">
                        <!-- Attendance Panel -->
                        <div class="tab-pane fade show active" id="attendance-panel" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 text-center">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>التاريخ</th>
                                            <th>المجموعة</th>
                                            <th>الموعد</th>
                                            <th>الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($attendances as $attendance)
                                            <tr>
                                                <td>{{ $attendance->session_date->format('Y/m/d') }}</td>
                                                <td>{{ $attendance->course->title }}</td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $attendance->schedule ? \Carbon\Carbon::parse($attendance->schedule->start_time)->format('h:i A') : '-' }}
                                                    </small>
                                                </td>
                                                <td>
                                                    @if($attendance->status == 'present')
                                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">حاضر</span>
                                                    @else
                                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">غائب</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="py-5 text-muted">لا يوجد سجل حضور مسجل حالياً.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Payments Panel -->
                        <div class="tab-pane fade" id="payments-panel" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 text-center">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>التاريخ</th>
                                            <th>المبلغ</th>
                                            <th>طريقة الدفع</th>
                                            <th>ملاحظات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($student->sales as $sale)
                                            <tr>
                                                <td>{{ $sale->created_at->format('Y/m/d') }}</td>
                                                <td class="fw-bold">{{ number_format($sale->paid_amount, 2) }} ج.م</td>
                                                <td>
                                                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">
                                                        {{ $sale->payment_method == 'cash' ? 'نقدي' : 'أخرى' }}
                                                    </span>
                                                </td>
                                                <td><small class="text-muted">{{ $sale->notes ?: '-' }}</small></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="py-5 text-muted">لا يوجد سجل مدفوعات مسجل حالياً.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-tabs .nav-link {
        color: #64748b;
        transition: all 0.3s;
        border-bottom: 2px solid transparent !important;
    }
    .nav-tabs .nav-link:hover {
        background: transparent;
        color: var(--primary-color);
    }
    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        background: transparent;
        border-bottom: 2px solid var(--primary-color) !important;
    }
</style>
<script>
    function copyPortalLink(url) {
        navigator.clipboard.writeText(url).then(() => {
            alert('تم نسخ رابط بوابة الطالب بنجاح!');
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }
</script>
@endsection
