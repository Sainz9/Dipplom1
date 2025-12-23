<?php

// 1. Autoload дуудах
require __DIR__ . '/../vendor/autoload.php';

// 2. App instance үүсгэх
$app = require __DIR__ . '/../bootstrap/app.php';

// === ЭНЭ ХЭСЭГ ХАМГИЙН ЧУХАЛ ===
// Vercel дээр зөвхөн /tmp руу бичиж болдог тул замыг өөрчилнө
$storage_path = '/tmp/storage';

if (!is_dir($storage_path)) {
    mkdir($storage_path, 0777, true);
    // Дэд хавтаснуудыг үүсгэх (алдаа гаргахгүйн тулд)
    mkdir($storage_path . '/logs', 0777, true);
    mkdir($storage_path . '/framework/views', 0777, true);
    mkdir($storage_path . '/framework/cache', 0777, true);
}

$app->useStoragePath($storage_path);
// ===============================

// 3. Laravel-ийг ажиллуулах
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);