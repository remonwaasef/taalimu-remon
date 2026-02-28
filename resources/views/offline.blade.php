<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لا يوجد اتصال بالإنترنت - EduCenter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/offline.css') }}">
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
