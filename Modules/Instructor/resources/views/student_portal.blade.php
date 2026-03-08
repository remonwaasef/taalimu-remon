<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة الطالب - {{ $student->name }}</title>
    
    <!-- Google Fonts (Cairo) -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    @if(app()->getLocale() == 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        :root {
            --primary: #4361EE;
            --secondary: #3A0CA3;
            --accent: #4CC9F0;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --bg: #f8fafc;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--bg);
            color: #1e293b;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .portal-header {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
            color: white;
            padding: 60px 0 100px;
            text-align: right;
            position: relative;
            overflow: hidden;
        }

        .portal-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: var(--bg);
            clip-path: polygon(0 100%, 100% 100%, 100% 0);
        }

        .portal-container {
            margin-top: -60px;
            position: relative;
            z-index: 10;
        }

        .portal-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .card-header-badge {
            background: #f1f5f9;
            padding: 16px 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header-badge h6 {
            margin: 0;
            font-weight: 800;
            color: #334155;
            font-size: 1rem;
        }

        .qr-wrapper {
            background: white;
            padding: 16px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            display: inline-block;
            border: 1px solid #edf2f7;
            width: 100%;
            max-width: 180px;
            margin-bottom: 20px;
        }

        .qr-wrapper svg {
            width: 100% !important;
            height: auto !important;
            display: block;
        }

        .balance-item {
            padding: 12px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed #e2e8f0;
        }

        .balance-item:last-child {
            border-bottom: none;
            padding-top: 16px;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .table > :not(caption) > * > * {
            padding: 16px 20px;
            vertical-align: middle;
        }

        .btn-action {
            padding: 12px 20px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-print {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-print:hover {
            background: var(--primary);
            color: white;
        }

        @media (max-width: 991px) {
            .portal-header {
                text-align: center;
                padding: 40px 0 80px;
            }
            .portal-container {
                margin-top: -40px;
            }
        }
    </style>
</head>
<body>

    <div class="portal-header">
        <div class="container">
            <h1 class="fw-bold mb-2">بوابة الطالب التعليمية</h1>
            <p class="opacity-75 mb-0">
                <i class="fas fa-user-circle me-2"></i> {{ $student->name }} | 
                <i class="fas fa-phone me-2"></i> {{ $student->phone }}
            </p>
        </div>
    </div>

    <div class="container portal-container pb-5">
        <div class="row g-4">
            <!-- Sidebar: QR & Info -->
            <div class="col-lg-4">
                <div class="portal-card p-4 text-center">
                    <h6 class="fw-bold mb-3">كود الحضور الشخصي</h6>
                    <div class="qr-wrapper shadow-sm">
                        <img src="{{ $qrCode }}" alt="QR Code" class="img-fluid">
                    </div>
                    <p class="small text-muted mb-4 px-3">يرجى الاحتفاظ بهذا الكود لإثبات حضورك عند الدخول للقاعة.</p>
                    <button onclick="window.print()" class="btn-action btn-print w-100">
                        <i class="fas fa-download"></i> تحميل كود QR
                    </button>
                </div>

                <div class="portal-card">
                    <div class="card-header-badge">
                        <h6><i class="fas fa-wallet me-2 text-primary"></i> الملخص المالي</h6>
                    </div>
                    <div class="p-4">
                        @php
                            $totalDue = $student->sales->sum('total_amount');
                            $totalPaid = $student->sales->sum('paid_amount');
                            $balance = $totalDue - $totalPaid;
                        @endphp
                        <div class="balance-item">
                            <span class="text-muted">إجمالي المستحق</span>
                            <span class="fw-bold">{{ number_format($totalDue, 0) }} ج.م</span>
                        </div>
                        <div class="balance-item">
                            <span class="text-muted">إجمالي المدفوع</span>
                            <span class="text-success fw-bold">{{ number_format($totalPaid, 0) }} ج.م</span>
                        </div>
                        <div class="balance-item border-0">
                            <span class="fw-bold text-dark">المتبقي المطلوب</span>
                            <span class="text-danger fw-bold h4 mb-0">{{ number_format($balance, 0) }} ج.م</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content: History -->
            <div class="col-lg-8">
                <!-- Attendance History -->
                <div class="portal-card">
                    <div class="card-header-badge">
                        <h6><i class="fas fa-calendar-check me-2 text-primary"></i> سجل الحضور الأخير</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 text-muted small fw-bold">التاريخ</th>
                                    <th class="border-0 text-muted small fw-bold">المجموعة</th>
                                    <th class="border-0 text-muted small fw-bold">الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $atten)
                                <tr>
                                    <td class="fw-bold text-dark">{{ \Carbon\Carbon::parse($atten->session_date)->format('Y-m-d') }}</td>
                                    <td>{{ $atten->course->title }}</td>
                                    <td>
                                        @if($atten->status == 'present')
                                            <span class="status-badge bg-success-subtle text-success border border-success-subtle">حاضر</span>
                                        @elseif($atten->status == 'late')
                                            <span class="status-badge bg-warning-subtle text-warning border border-warning-subtle">متأخر</span>
                                        @else
                                            <span class="status-badge bg-danger-subtle text-danger border border-danger-subtle">غائب</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">لا يوجد سجلات حضور حتى الآن</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment History -->
                <div class="portal-card">
                    <div class="card-header-badge">
                        <h6><i class="fas fa-receipt me-2 text-primary"></i> سجل المعاملات المالية</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse($sales as $sale)
                        <div class="list-group-item p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-box bg-light rounded-circle p-3 text-primary">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold">تحصيل مبلغ: {{ number_format($sale->paid_amount, 0) }} ج.م</h6>
                                        <small class="text-muted"><i class="far fa-clock me-1"></i> {{ $sale->created_at->format('Y-m-d h:i A') }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-light text-dark border rounded-pill px-3">{{ $sale->payment_method }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="p-5 text-center text-muted">لا يوجد عمليات دفع مسجلة</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
