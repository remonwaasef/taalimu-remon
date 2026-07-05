<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كارنيه الطالب | {{ $student->name }}</title>
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/hope-ui.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap');
        
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .id-card-container {
            position: relative;
            width: 350px;
            height: 620px;
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
            overflow: hidden;
            text-align: center;
        }

        .card-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            height: 150px;
            padding: 20px;
            color: white;
            position: relative;
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: -40px;
            left: 0;
            width: 100%;
            height: 80px;
            background: white;
            border-radius: 50%;
            z-index: 1;
        }

        .academy-logo {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 2px;
            letter-spacing: 1px;
        }

        .student-photo-wrapper {
            position: relative;
            z-index: 2;
            margin-top: -50px;
        }

        .student-photo {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            background-color: #f8f9fa;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .student-name {
            margin-top: 15px;
            font-size: 1.3rem;
            font-weight: 700;
            color: #1f2937;
            padding: 0 20px;
        }

        .student-info {
            margin-top: 15px;
            padding: 0 30px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #eee;
        }

        .info-label {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .info-value {
            color: #111827;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .card-footer {
            margin-top: 20px;
            padding: 15px;
            background: #f9fafb;
        }

        .qr-code-badge {
            position: absolute;
            top: 95px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 120px;
            background: white;
            padding: 8px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            z-index: 10;
            border: 3px solid white;
        }

        .qr-code-badge img {
            width: 100%;
            height: 100%;
            display: block;
        }

        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #10b981;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: bold;
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
            cursor: pointer;
            z-index: 100;
            transition: all 0.3s;
        }

        .print-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(16, 185, 129, 0.4);
        }

        @media print {
            body { background: white; }
            .print-btn { display: none; }
            .id-card-container { box-shadow: none; border: 1px solid #eee; }
        }
    </style>
</head>
<body>

    <button class="print-btn" onclick="window.print()">
        <i class="fas fa-print me-2"></i> طباعة الكارنيه
    </button>

    <div class="id-card-container">
        <div class="card-header position-relative">
            <div class="academy-logo pt-2">
                <i class="fas fa-graduation-cap me-1"></i> {{ app('tenant')->name ?? 'أكاديمية تعليم' }}
            </div>
            <div class="extra-small opacity-75">بطاقة تعريف الطالب الرقمية</div>
            
            <div class="qr-code-badge">
                {{-- QR generated locally in the browser (no third-party service) --}}
                <div id="idCardQr" data-qr="{{ url('/login?student_id='.$student->id) }}"></div>
            </div>
        </div>

        <div class="student-photo-wrapper" style="margin-top: 65px; margin-bottom: 10px;">
            <div class="d-flex align-items-center justify-content-center">
                @if($student->profile_photo)
                    <img src="{{ asset('storage/'.$student->profile_photo) }}" alt="{{ $student->name }}" class="student-photo" style="width: 100px; height: 100px;">
                @else
                    <div class="student-photo d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 100px; height: 100px; font-size: 3rem; background: #e0f2fe;">
                        {{ mb_substr($student->name, 0, 1) }}
                    </div>
                @endif
            </div>
        </div>

        <div class="student-name">{{ $student->name }}</div>
        
        <div class="student-info">
            <div class="info-row">
                <span class="info-label">المرحلة / الصف</span>
                <span class="info-value">{{ $student->grade->stage->name ?? '-' }} / {{ $student->grade->name ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">رقم الهاتف</span>
                <span class="info-value" dir="ltr">{{ $student->phone }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">كود الطالب</span>
                <span class="info-value">#{{ $student->id }}</span>
            </div>
        </div>

        <div class="mt-4 px-3">
            <div class="p-2 rounded-pill bg-light x-small text-muted border">
                <i class="fas fa-info-circle me-1"></i> امسح الكود لتسجيل الحضور المباشر
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.getElementById('idCardQr');
            if (el && typeof QRCode !== 'undefined' && el.dataset.qr) {
                new QRCode(el, { text: el.dataset.qr, width: 70, height: 70, correctLevel: QRCode.CorrectLevel.H });
            }
        });
        // Optional: Auto-trigger print
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
