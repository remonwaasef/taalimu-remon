<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\Illuminate\Support\Facades\Artisan::call('optimize:clear');
\Illuminate\Support\Facades\Artisan::call('view:clear');

echo "<div style='text-align:center; padding: 50px; font-family: tahoma, sans-serif;' dir='rtl'>";
echo "<h1 style='color: green;'>✅ تم مسح التخزين المؤقت (Cache) بنجاح!</h1>";
echo "<h3>يمكنك الآن العودة لموقعك وعمل تحديث للمتصفح.</h3>";
echo "<p style='color:red;'>ملاحظة: للحماية، يرجى حذف هذا الملف (clear.php) من السيرفر بعد الانتهاء.</p>";
echo "</div>";
