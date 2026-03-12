<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل طالب جديد - {{ $course->title }}</title>
    
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
        :root {
            --primary: #4361EE;
            --secondary: #3A0CA3;
            --accent: #4CC9F0;
            --success: #4CAF50;
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

        .register-container {
            width: 100%;
            max-width: 480px;
            position: relative;
        }

        /* Decorative blobs for premium feel */
        .blob {
            position: absolute;
            width: 200px;
            height: 200px;
            background: var(--primary);
            filter: blur(80px);
            opacity: 0.15;
            z-index: -1;
            border-radius: 50%;
        }
        .blob-1 { top: -50px; left: -50px; background: var(--accent); }
        .blob-2 { bottom: -50px; right: -50px; background: var(--secondary); }

        .register-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: right;
            transition: transform 0.3s ease;
        }

        .course-chip {
            display: inline-flex;
            align-items: center;
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
            padding: 6px 16px;
            border-radius: 100px;
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 24px;
            border: 1px solid rgba(67, 97, 238, 0.2);
        }

        .register-header h2 {
            font-weight: 800;
            color: #1a202c;
            font-size: 1.75rem;
            margin-bottom: 8px;
        }

        .register-header p {
            color: #718096;
            margin-bottom: 32px;
            font-size: 0.95rem;
        }

        .form-label {
            font-weight: 700;
            color: #4a5568;
            margin-bottom: 10px;
            font-size: 0.9rem;
            display: block;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-custom i {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            transition: color 0.3s ease;
        }

        .form-control {
            border-radius: 14px;
            padding: 14px 45px 14px 16px !important;
            border: 2px solid #edf2f7;
            background: #f8fafc;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: none !important;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: #fff;
        }

        .form-control:focus + i {
            color: var(--primary);
        }

        .btn-register {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 16px;
            font-weight: 700;
            width: 100%;
            margin-top: 24px;
            font-size: 1.1rem;
            box-shadow: 0 10px 20px -5px rgba(67, 97, 238, 0.4);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(67, 97, 238, 0.5);
            color: white;
        }

        .info-box {
            margin-top: 30px;
            padding: 16px;
            background: #fdf2f2;
            border-radius: 14px;
            border: 1px solid #fee2e2;
            color: #b91c1c;
            font-size: 0.85rem;
            display: none; /* Show on validation error if needed */
        }

        @media (max-width: 576px) {
            .register-card {
                padding: 30px 20px;
            }
            .register-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <div class="register-container">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>

        <div class="register-card">
            <div class="text-center">
                <span class="course-chip">
                    <i class="fas fa-graduation-cap me-2"></i> {{ $course->title }}
                </span>
            </div>
            
            <div class="register-header text-center">
                <h2>تسجيل طالب جديد</h2>
                <p>انضم الآن للمجموعة وابدأ رحلة تعلم ممتعة</p>
            </div>

            <form action="{{ route('group.register.submit', $course->registration_token) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="name" class="form-label">اسم الطالب بالكامل</label>
                    <div class="input-group-custom">
                        <input type="text" class="form-control" id="name" name="name" required placeholder="أدخل اسمك كما في الهوية">
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="phone" class="form-label">رقم الهاتف (واتساب)</label>
                    <div class="input-group-custom">
                        <input type="tel" class="form-control" id="phone" name="phone" required minlength="11" maxlength="11" pattern="[0-9]{11}" title="يجب أن يكون رقم الهاتف مكون من 11 رقم" placeholder="01xxxxxxxxx">
                        <i class="fas fa-phone"></i>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label">البريد الإلكتروني (اختياري)</label>
                    <div class="input-group-custom">
                        <input type="email" class="form-control" id="email" name="email" placeholder="example@mail.com">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="parent_phone" class="form-label">رقم هاتف ولي الأمر</label>
                    <div class="input-group-custom">
                        <input type="tel" class="form-control" id="parent_phone" name="parent_phone" required minlength="11" maxlength="11" pattern="[0-9]{11}" title="يجب أن يكون رقم الهاتف مكون من 11 رقم" placeholder="01xxxxxxxxx">
                        <i class="fas fa-user-friends"></i>
                    </div>
                </div>

                <button type="submit" class="btn btn-register">
                    <i class="fas fa-user-plus me-2"></i> تأكيد الانضمام للمجموعة
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-muted small">لديك حساب بالفعل؟ <a href="/login" class="text-primary fw-bold text-decoration-none">تسجيل الدخول</a></p>
            </div>
        </div>
    </div>

</body>
</html>
