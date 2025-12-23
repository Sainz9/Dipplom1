<?php

require __DIR__ . '/../vendor/autoload.php';

// 1. Vercel дээр бичих эрхтэй цор ганц газар болох /tmp замыг бэлдэх
$tmpDir = '/tmp';
$storageDir = $tmpDir . '/storage';
$bootstrapCacheDir = $tmpDir . '/bootstrap/cache';

// 2. /tmp дотор шаардлагатай хавтаснуудыг үүсгэх (байхгүй бол)
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0777, true);
    // Storage доторх дэд хавтаснууд
    mkdir($storageDir . '/logs', 0777, true);
    mkdir($storageDir . '/framework/views', 0777, true);
    mkdir($storageDir . '/framework/sessions', 0777, true);
    mkdir($storageDir . '/framework/cache', 0777, true);
}

if (!is_dir($bootstrapCacheDir)) {
    mkdir($bootstrapCacheDir, 0777, true);
}

// 3. Laravel-д cache файлуудаа /tmp руу хадгал гэж тушаах (Environment Variable ашиглан)
// Энэ нь таны гарч буй "bootstrap/cache must be writable" алдааг засна.
putenv("APP_PACKAGES_CACHE={$bootstrapCacheDir}/packages.php");
putenv("APP_SERVICES_CACHE={$bootstrapCacheDir}/services.php");
putenv("APP_CONFIG_CACHE={$bootstrapCacheDir}/config.php");
putenv("APP_ROUTES_CACHE={$bootstrapCacheDir}/routes-v7.php");
putenv("APP_EVENTS_CACHE={$bootstrapCacheDir}/events.php");

// 4. Үндсэн App-аа дуудах
$app = require __DIR__ . '/../bootstrap/app.php';

// 5. Storage замыг албан ёсоор солих
$app->useStoragePath($storageDir);

// 6. App ажиллуулах
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);