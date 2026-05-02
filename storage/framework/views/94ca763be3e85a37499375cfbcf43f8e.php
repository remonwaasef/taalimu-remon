<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم التسجيل بنجاح - <?php echo e(config('app.name')); ?></title>
    
    <!-- Google Fonts (Cairo) -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <?php if(app()->getLocale() == 'ar'): ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <?php else: ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php endif; ?>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        :root {
            --primary: #4361EE;
            --secondary: #3A0CA3;
            --accent: #4CC9F0;
            --success: #10b981;
            --bg-gradient: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
            overflow-x: hidden;
        }

        .success-container {
            width: 100%;
            max-width: 480px;
            position: relative;
        }

        /* Decorative blobs */
        .blob {
            position: absolute;
            width: 250px;
            height: 250px;
            background: var(--primary);
            filter: blur(100px);
            opacity: 0.1;
            z-index: -1;
            border-radius: 50%;
        }
        .blob-1 { top: -80px; left: -80px; background: var(--success); }
        .blob-2 { bottom: -80px; right: -80px; background: var(--accent); }

        .success-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            padding: 40px;
            text-align: center;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .icon-box {
            width: 80px;
            height: 80px;
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 24px;
            font-size: 2.5rem;
            border: 2px solid rgba(16, 185, 129, 0.2);
        }

        .success-card h3 {
            font-weight: 800;
            color: #1a202c;
            margin-bottom: 12px;
        }

        .success-card p {
            color: #718096;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .qr-wrapper {
            background: white;
            padding: 24px;
            border-radius: 24px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            margin: 24px 0;
            display: inline-block;
            border: 1px solid #edf2f7;
            width: 100%;
            max-width: 250px;
        }

        .qr-wrapper svg {
            width: 100% !important;
            height: auto !important;
            display: block;
        }

        .info-alert {
            background: #eff6ff;
            border: 1px solid #dbeafe;
            color: #1e40af;
            border-radius: 16px;
            padding: 16px;
            font-size: 0.85rem;
            margin-bottom: 32px;
            text-align: right;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-portal {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 16px;
            font-weight: 700;
            width: 100%;
            margin-bottom: 12px;
            font-size: 1.05rem;
            box-shadow: 0 10px 20px -5px rgba(67, 97, 238, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-portal:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(67, 97, 238, 0.5);
        }

        .btn-download {
            background: #fff;
            color: #4a5568;
            border: 2px solid #edf2f7;
            padding: 14px;
            border-radius: 16px;
            font-weight: 700;
            width: 100%;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-download:hover {
            background: #f8fafc;
            border-color: #cbd5e0;
        }

        @media (max-width: 576px) {
            .success-card {
                padding: 32px 20px;
                border-radius: 24px;
            }
            .icon-box {
                width: 64px;
                height: 64px;
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

    <div class="success-container">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>

        <div class="success-card">
            <div class="icon-box">
                <i class="fas fa-check"></i>
            </div>
            
            <h3>تم تسجيلك بنجاح!</h3>
            <p>مرحباً بك يا <strong><?php echo e($user->name); ?></strong>. لقد تم تسجيلك في المجموعة بنجاح. يرجى الاحتفاظ بكود الحضور أدناه.</p>

            <div class="qr-wrapper shadow-sm bg-white p-3 rounded-4 mb-4 text-center">
                <div id="qrcode-container" class="d-flex justify-content-center"></div>
                <div class="mt-3">
                    <code class="text-primary fw-bold fs-5">#<?php echo e($user->qr_identifier); ?></code>
                </div>
            </div>

            <div class="info-alert">
                <i class="fas fa-info-circle fs-5"></i>
                <div>
                    يمكنك الدخول لبوابتك التعليمية لاحقاً باستخدام <strong>رقم هاتفك</strong> ككلمة سر افتراضية.
                </div>
            </div>

            <div class="d-flex flex-column gap-3">
                <a href="<?php echo e(route('student.portal', $user->qr_identifier)); ?>" class="btn-portal">
                    <i class="fas fa-rocket"></i> دخول بوابة الطالب
                </a>
                
                <button onclick="downloadQR()" class="btn-download">
                    <i class="fas fa-download"></i> حفظ كود الدخول
                </button>
            </div>
            
            <div class="mt-4">
                <a href="/" class="text-muted small text-decoration-none">
                    <i class="fas fa-arrow-right me-1"></i> العودة للرئيسية
                </a>
            </div>
        </div>
    </div>

    <!-- QR Code Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qrContainer = document.getElementById('qrcode-container');
            const identifier = "<?php echo e($user->qr_identifier); ?>";
            
            if (typeof QRCode !== 'undefined' && qrContainer) {
                new QRCode(qrContainer, {
                    text: identifier,
                    width: 200,
                    height: 200,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });
            }
        });

        function downloadQR() {
            const qrContainer = document.getElementById('qrcode-container');
            const qrCanvas = qrContainer.querySelector('canvas');
            const studentName = "<?php echo e($user->name); ?>";
            const studentId = "<?php echo e($user->qr_identifier); ?>";
            const courseTitle = "<?php echo e($course->title ?? ''); ?>";
            const instructorName = "<?php echo e($course->instructor->name ?? ''); ?>";
            const fileName = `QR_${studentId}.png`;

            if (!qrCanvas) {
                alert("عذراً، تعذر تحميل الكود. حاول مرة أخرى.");
                return;
            }

            // Create a composite canvas to combine QR and Text
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            
            const padding = 20;
            const qrSize = 400; // Increase for better quality
            const textHeight = 140; // Increased to fit more lines
            const totalWidth = qrSize + (padding * 2);
            const totalHeight = qrSize + textHeight + (padding * 2);

            canvas.width = totalWidth;
            canvas.height = totalHeight;

            // Fill background
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Draw QR Code
            ctx.drawImage(qrCanvas, padding, padding, qrSize, qrSize);

            // Draw Student Info
            ctx.fillStyle = '#000000';
            ctx.font = 'bold 24px Arial, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(studentName, totalWidth / 2, padding + qrSize + 30);
            
            ctx.font = '18px Arial, sans-serif';
            ctx.fillStyle = '#64748b'; // Gray text for group
            let currentY = padding + qrSize + 60;
            
            if (courseTitle) {
                ctx.fillText(`المجموعة: ${courseTitle}`, totalWidth / 2, currentY);
                currentY += 25;
            }
            
            if (instructorName) {
                ctx.fillText(`المدرس: ${instructorName}`, totalWidth / 2, currentY);
                currentY += 25;
            }
            
            ctx.font = '20px monospace';
            ctx.fillStyle = '#4f46e5'; // Primary color
            ctx.fillText(`#${studentId}`, totalWidth / 2, currentY + 10);

            // Trigger Download
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
<?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\groups\registration_success.blade.php ENDPATH**/ ?>