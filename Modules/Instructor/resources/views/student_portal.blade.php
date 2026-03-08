<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة الطالب - {{ $student->name }}</title>
    
    <!-- Google Fonts (Cairo) -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    @if(app()->getLocale() == 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }
        .student-header {
            background: linear-gradient(135deg, #3A0CA3 0%, #4361EE 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: -50px;
        }
        .portal-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }
        .qr-small {
            background: white;
            padding: 10px;
            border-radius: 12px;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="student-header">
        <div class="container text-center">
            <h2 class="fw-bold">بوابة الطالب الخاصة بك</h2>
            <p>{{ $student->name }} | {{ $student->phone }}</p>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row g-4">
            <!-- Sidebar: QR & Info -->
            <div class="col-lg-4">
                <div class="portal-card text-center p-4">
                    <h6 class="fw-bold mb-3">كود الحضور (QR)</h6>
                    <div class="qr-small shadow-sm mb-3">
                        {!! QrCode::size(150)->generate($user->qr_identifier) !!}
                    </div>
                    <p class="small text-muted mb-4 text-center">أظهر هذا الكود للمدرس عند الدخول لتسجيل حضورك</p>
                    <button onclick="window.print()" class="btn btn-outline-primary rounded-pill w-100 mb-2">
                        <i class="fas fa-download me-2"></i> تحميل الكود
                    </button>
                </div>

                <div class="portal-card p-4">
                    <h6 class="fw-bold mb-3">ملخص الحساب</h6>
                    @php
                        $totalDue = $student->sales->sum('total_amount');
                        $totalPaid = $student->sales->sum('paid_amount');
                        $balance = $totalDue - $totalPaid;
                    @endphp
                    <div class="d-flex justify-content-between mb-2">
                        <span>إجمالي المطلوب:</span>
                        <span class="fw-bold">{{ number_format($totalDue, 0) }} ج.م</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>إجمالي المدفوع:</span>
                        <span class="text-success fw-bold">{{ number_format($totalPaid, 0) }} ج.م</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">المتبقي:</span>
                        <span class="text-danger fw-bold h5 mb-0">{{ number_format($balance, 0) }} ج.م</span>
                    </div>
                </div>
            </div>

            <!-- Main Content: History -->
            <div class="col-lg-8">
                <!-- Attendance History -->
                <div class="portal-card">
                    <div class="p-4 border-bottom">
                        <h6 class="fw-bold mb-0">سجل الحضور الأخير</h6>
                    </div>
                    <div class="p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-4">التاريخ</th>
                                        <th class="border-0">المجموعة</th>
                                        <th class="border-0">الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances as $atten)
                                    <tr>
                                        <td class="px-4 fw-bold">{{ \Carbon\Carbon::parse($atten->session_date)->format('Y-m-d') }}</td>
                                        <td>{{ $atten->course->title }}</td>
                                        <td>
                                            @if($atten->status == 'present')
                                                <span class="badge bg-success rounded-pill px-3">حاضر</span>
                                            @elseif($atten->status == 'late')
                                                <span class="badge bg-warning text-dark rounded-pill px-3">متأخر</span>
                                            @else
                                                <span class="badge bg-danger rounded-pill px-3">غائب</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Payment History -->
                <div class="portal-card">
                    <div class="p-4 border-bottom">
                        <h6 class="fw-bold mb-0">سجل المدفوعات</h6>
                    </div>
                    <div class="p-0">
                        <div class="list-group list-group-flush">
                            @foreach($sales as $sale)
                            <div class="list-group-item px-4 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-bold">تحصيل مبلغ: {{ number_format($sale->paid_amount, 0) }} ج.م</h6>
                                        <small class="text-muted">{{ $sale->created_at->format('Y-m-d h:i A') }}</small>
                                    </div>
                                    <span class="badge bg-light text-dark border rounded-pill">{{ $sale->payment_method }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
