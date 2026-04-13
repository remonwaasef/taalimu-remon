<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('instructor::portal.title', ['name' => $student->name]) }}</title>
    
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
    <!-- qrcode.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>

    <div class="portal-header">
        <div class="container">
            <h1 class="fw-bold mb-2">{{ __('instructor::portal.header') }}</h1>
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
                    <h6 class="fw-bold mb-3">{{ __('instructor::portal.personal_qr') }}</h6>
                    <div class="qr-wrapper shadow-sm mx-auto">
                        <div id="qrcode-container" class="d-flex justify-content-center"></div>
                    </div>
                    <p class="small text-muted mb-4 px-3">{{ __('instructor::portal.qr_hint') }}</p>
                    <button onclick="downloadQR()" class="btn-action btn-print w-100">
                        <i class="fas fa-download"></i> {{ __('instructor::portal.download_qr') }}
                    </button>
                </div>

                <div class="portal-card">
                    <div class="card-header-badge">
                        <h6><i class="fas fa-wallet me-2 text-primary"></i> {{ __('instructor::portal.financial_summary') }}</h6>
                    </div>
                    <div class="p-4">
                        @php
                            $totalDue = $student->sales->sum('total_amount');
                            $totalPaid = $student->sales->sum('paid_amount');
                            $balance = $totalDue - $totalPaid;
                        @endphp
                        <div class="balance-item">
                            <span class="text-muted">{{ __('instructor::portal.total_due') }}</span>
                            <span class="fw-bold">{{ number_format($totalDue, 0) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}</span>
                        </div>
                        <div class="balance-item">
                            <span class="text-muted">{{ __('instructor::portal.total_paid') }}</span>
                            <span class="text-success fw-bold">{{ number_format($totalPaid, 0) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}</span>
                        </div>
                        <div class="balance-item border-0">
                            <span class="fw-bold text-dark">{{ __('instructor::portal.balance_required') }}</span>
                            <span class="text-danger fw-bold h4 mb-0">{{ number_format($balance, 0) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content: History -->
            <div class="col-lg-8">
                <!-- Upcoming Online Classes -->
                @if(isset($onlineClasses) && $onlineClasses->count() > 0)
                <div class="portal-card" style="border-right: 4px solid var(--success);">
                    <div class="card-header-badge">
                        <h6><i class="fas fa-video me-2 text-success"></i> {{ __('instructor::dashboard.online_classes') ?? 'الدروس الأونلاين القادمة' }}</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 text-muted small fw-bold">الدرس وموعد البدء</th>
                                    <th class="border-0 text-muted small fw-bold text-center">الرابط</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($onlineClasses as $lesson)
                                <tr>
                                    <td>
                                        <div class="fw-bold mb-1">{{ $lesson->title }}</div>
                                        <div class="text-muted small">
                                            <i class="far fa-clock me-1"></i> {{ $lesson->start_time->format('Y-m-d h:i A') }}
                                            <span class="badge bg-light text-dark ms-2">{{ ucfirst($lesson->platform) }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{ $lesson->meeting_link }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">
                                            <i class="fas fa-play me-1"></i> انضمام للدرس
                                        </a>
                                        @if($lesson->meeting_password)
                                            <div class="text-muted small mt-1">الباسوورد: <code>{{ $lesson->meeting_password }}</code></div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Attendance History -->
                <div class="portal-card">
                    <div class="card-header-badge">
                        <h6><i class="fas fa-calendar-check me-2 text-primary"></i> {{ __('instructor::portal.attendance_record') }}</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 text-muted small fw-bold">{{ __('instructor::portal.date') }}</th>
                                    <th class="border-0 text-muted small fw-bold">{{ __('instructor::portal.group') }}</th>
                                    <th class="border-0 text-muted small fw-bold">{{ __('instructor::portal.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $atten)
                                <tr>
                                    <td class="fw-bold text-dark">{{ \Carbon\Carbon::parse($atten->session_date)->format('Y-m-d') }}</td>
                                    <td>{{ $atten->course->title }}</td>
                                    <td>
                                        @if($atten->status == 'present')
                                            <span class="status-badge bg-success-subtle text-success border border-success-subtle">{{ __('instructor::portal.present') }}</span>
                                        @elseif($atten->status == 'late')
                                            <span class="status-badge bg-warning-subtle text-warning border border-warning-subtle">{{ __('instructor::portal.late') }}</span>
                                        @else
                                            <span class="status-badge bg-danger-subtle text-danger border border-danger-subtle">{{ __('instructor::portal.absent') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">{{ __('instructor::portal.no_attendance') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment History -->
                <div class="portal-card">
                    <div class="card-header-badge">
                        <h6><i class="fas fa-receipt me-2 text-primary"></i> {{ __('instructor::portal.payment_record') }}</h6>
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
                                        <h6 class="mb-1 fw-bold">{{ __('instructor::portal.collected_amount', ['amount' => number_format($sale->paid_amount, 0)]) }}</h6>
                                        <small class="text-muted"><i class="far fa-clock me-1"></i> {{ $sale->created_at->format('Y-m-d h:i A') }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-light text-dark border rounded-pill px-3">{{ $sale->payment_method }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="p-5 text-center text-muted">{{ __('instructor::portal.no_payments') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qrContainer = document.getElementById('qrcode-container');
            const qrData = "{{ $user->qr_identifier }}";
            
            if (qrContainer && qrData) {
                new QRCode(qrContainer, {
                    text: qrData,
                    width: 180,
                    height: 180,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });
            }
        });

        function downloadQR() {
            const qrContainer = document.getElementById('qrcode-container');
            const qrCanvas = qrContainer.querySelector('canvas');
            const studentName = "{{ $student->name }}";
            const studentId = "{{ $user->qr_identifier }}";
            const courseTitle = "{{ $course->title ?? '' }}";
            const instructorName = "{{ $course->instructor->name ?? '' }}";
            const fileName = `QR_${studentId}.png`;

            if (!qrCanvas) {
                alert("{{ __('instructor::portal.qr_error') }}");
                return;
            }

            // Create a composite canvas
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            
            const padding = 20;
            const qrSize = 400; 
            const textHeight = 140; // Increased for more lines
            const totalWidth = qrSize + (padding * 2);
            const totalHeight = qrSize + textHeight + (padding * 2);

            canvas.width = totalWidth;
            canvas.height = totalHeight;

            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            ctx.drawImage(qrCanvas, padding, padding, qrSize, qrSize);

            ctx.fillStyle = '#000000';
            ctx.font = 'bold 24px Cairo, Arial, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(studentName, totalWidth / 2, padding + qrSize + 30);
            
            ctx.font = '18px Cairo, Arial, sans-serif';
            ctx.fillStyle = '#64748b';
            let currentY = padding + qrSize + 60;
            
            if (courseTitle) {
                ctx.fillText(`{{ __('instructor::portal.group') }}: ${courseTitle}`, totalWidth / 2, currentY);
                currentY += 25;
            }
            
            if (instructorName) {
                ctx.fillText(`{{ __('instructor::sidebar.instructor') }}: ${instructorName}`, totalWidth / 2, currentY);
                currentY += 25;
            }
            
            ctx.font = '20px monospace';
            ctx.fillStyle = '#4f46e5';
            ctx.fillText(`#${studentId}`, totalWidth / 2, currentY + 10);

            const dataUrl = canvas.toDataURL("image/png");
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = dataUrl;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    </script>
</body>
</html>
