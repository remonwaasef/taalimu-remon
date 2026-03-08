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
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
            padding: 40px;
        }
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-header h2 {
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }
        .btn-register {
            background: linear-gradient(135deg, #3A0CA3 0%, #4361EE 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            margin-top: 20px;
        }
        .form-label {
            font-weight: 600;
            color: #555;
        }
        .course-badge {
            display: inline-block;
            background: #e9ecef;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.9rem;
            color: #495057;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="register-card">
        <div class="register-header">
            <span class="course-badge">{{ $course->title }}</span>
            <h2>تسجيل طالب جديد</h2>
            <p class="text-muted small">يرجى ملء البيانات التالية للانضمام للمجموعة</p>
        </div>

        <form action="{{ route('group.register.submit', $course->registration_token) }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">اسم الطالب بالكامل</label>
                <input type="text" class="form-control" id="name" name="name" required placeholder="أدخل اسمك كما في البطاقة">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">رقم هاتف الطالب (واتساب)</label>
                <input type="tel" class="form-control" id="phone" name="phone" required placeholder="01xxxxxxxxx">
            </div>

            <div class="mb-3">
                <label for="parent_phone" class="form-label">رقم هاتف ولي الأمر (اختياري)</label>
                <input type="tel" class="form-control" id="parent_phone" name="parent_phone" placeholder="01xxxxxxxxx">
            </div>

            <button type="submit" class="btn btn-register shadow">تأكيد التسجيل</button>
        </form>
    </div>

</body>
</html>
