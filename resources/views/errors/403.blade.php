<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعذر الوصول - Taalimu</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    
    <style>
        :root {
            --primary-color: #0f8b65; /* Premium Emerald */
            --secondary-color: #0b664a;
            --accent-color: #1abc9c;
            --text-main: #2b3445;
            --text-muted: #7d879c;
            --bg-light: #f8f9fa;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--bg-light);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        /* Abstract Premium Background Shapes */
        .bg-shape {
            position: absolute;
            z-index: 0;
            opacity: 0.1;
        }
        
        .shape-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 50%;
            top: -100px;
            right: -100px;
            filter: blur(60px);
        }

        .shape-2 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
            filter: blur(50px);
        }

        .error-container {
            position: relative;
            z-index: 10;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 4rem 3rem;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05), 0 1px 3px rgba(0,0,0,0.03);
            max-width: 500px;
            width: 90%;
            border: 1px solid rgba(255,255,255,0.8);
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
            background: rgba(15, 139, 101, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: var(--primary-color);
            font-size: 3rem;
            position: relative;
            box-shadow: inset 0 0 0 2px rgba(15, 139, 101, 0.2);
            animation: pulse 2s infinite;
        }

        .error-code {
            font-size: 5rem;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--text-main), var(--text-muted));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 1rem;
        }

        .error-desc {
            color: var(--text-muted);
            font-size: 1rem;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .btn-premium {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(15, 139, 101, 0.3);
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(15, 139, 101, 0.4);
            color: white;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(15, 139, 101, 0.4); }
            70% { box-shadow: 0 0 0 20px rgba(15, 139, 101, 0); }
            100% { box-shadow: 0 0 0 0 rgba(15, 139, 101, 0); }
        }

        .system-branding {
            position: absolute;
            bottom: 2rem;
            left: 0;
            right: 0;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 1px;
            z-index: 10;
        }
    </style>
</head>
<body>

    <!-- Background Decoration -->
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <div class="error-container">
        <div class="icon-wrapper">
            <i class="fas fa-user-shield"></i>
        </div>
        
        <div class="error-code">403</div>
        <h1 class="error-title">عفواً، لا تملك الصلاحية</h1>
        
        <p class="error-desc">
            يبدو أنك تحاول الوصول إلى صفحة أو تنفيذ إجراء يتطلب صلاحيات أعلى. يرجى التواصل مع مدير النظام إذا كنت تعتقد أن هذا خطأ.
        </p>

        <a href="javascript:history.back()" class="btn-premium">
            <i class="fas fa-arrow-right"></i>
            العودة للصفحة السابقة
        </a>
    </div>

    <div class="system-branding">
        Powered by <span style="color: var(--primary-color);">Taalimu</span> System
    </div>

</body>
</html>
