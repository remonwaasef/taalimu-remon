<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$server = [
    'HTTP_HOST' => 'localhost',
    'SERVER_NAME' => 'localhost',
    'SERVER_PORT' => 80,
    'REMOTE_ADDR' => '127.0.0.1',
    'REQUEST_URI' => '/admin/login',
    'REQUEST_METHOD' => 'GET',
    'HTTP_USER_AGENT' => 'Mozilla/5.0 (Test)',
];
$request = Illuminate\Http\Request::create('/admin/login', 'GET', [], [], [], $server);
$response = $kernel->handle($request);
$cookies = [];
foreach ($response->headers->getCookies() as $c) {
    if (in_array($c->getName(), ['XSRF-TOKEN', 'laravel_session'])) {
        $cookies[$c->getName()] = urldecode($c->getValue());
    }
}
preg_match('/name="_token" value="([^"]+)"/', $response->getContent(), $m);
$token = $m[1] ?? '';
$kernel->terminate($request, $response);
\Illuminate\Support\Facades\Facade::clearResolvedInstances();

$server2 = [
    'HTTP_HOST' => 'localhost',
    'SERVER_NAME' => 'localhost',
    'SERVER_PORT' => 80,
    'REMOTE_ADDR' => '127.0.0.1',
    'REQUEST_URI' => '/admin/login',
    'REQUEST_METHOD' => 'POST',
    'HTTP_USER_AGENT' => 'Mozilla/5.0 (Test)',
    'HTTP_COOKIE' => implode('; ', array_map(fn($k, $v) => $k.'='.$v, array_keys($cookies), $cookies)),
];
$request2 = Illuminate\Http\Request::create('/admin/login', 'POST', ['_token' => $token, 'email' => 'admin@admin.com', 'password' => 'password'], [], [], $server2);
$response2 = $kernel->handle($request2);
$kernel->terminate($request2, $response2);
\Illuminate\Support\Facades\Facade::clearResolvedInstances();

$server3 = [
    'HTTP_HOST' => 'localhost',
    'SERVER_NAME' => 'localhost',
    'SERVER_PORT' => 80,
    'REMOTE_ADDR' => '127.0.0.1',
    'REQUEST_URI' => '/admin/settings',
    'REQUEST_METHOD' => 'GET',
    'HTTP_USER_AGENT' => 'Mozilla/5.0 (Test)',
    'HTTP_COOKIE' => implode('; ', array_map(fn($k, $v) => $k.'='.$v, array_keys($cookies), $cookies)),
];
$request3 = Illuminate\Http\Request::create('/admin/settings', 'GET', [], [], [], $server3);
$response3 = $kernel->handle($request3);
$html = $response3->getContent();
file_put_contents(__DIR__.'/scratch_settings.html', $html);
echo 'Status: '.$response3->getStatusCode().' | HTML size: '.strlen($html)."\n";
$kernel->terminate($request3, $response3);
