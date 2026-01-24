<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لا يوجد اتصال بالإنترنت - EduCenter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .offline-container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            max-width: 500px;
            width: 90%;
        }
        .icon {
            font-size: 4rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }
        h1 {
            color: #343a40;
            margin-bottom: 0.5rem;
        }
        p {
            color: #6c757d;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="offline-container">
        <div class="icon">📡</div>
        <h1>لا يوجد اتصال بالإنترنت</h1>
        <p>يبدو أنك فقدت الاتصال بالإنترنت. يرجى التحقق من الشبكة والمحاولة مرة أخرى.</p>
        <button onclick="window.location.reload()" class="btn btn-primary btn-lg">إعادة المحاولة</button>
    </div>
</body>
</html>
