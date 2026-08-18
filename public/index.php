<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
$cacheConfigPath = __DIR__.'/../bootstrap/cache/config.php';
$cacheRoutesPath = __DIR__.'/../bootstrap/cache/routes-v7.php';

if (file_exists($cacheConfigPath)) {
    @unlink($cacheConfigPath);
}
if (file_exists($cacheRoutesPath)) {
    @unlink($cacheRoutesPath);
}

$viewsPath = __DIR__.'/../storage/framework/views';
if (!is_dir($viewsPath)) {
    @mkdir($viewsPath, 0775, true);
}

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// --- AUTO MIGRATE SCRIPT ---
if ($request->is('auto-migrate')) {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        echo "Migrations ran successfully!<br>Output: " . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Exception $e) {
        echo "Migration failed: " . $e->getMessage();
    }
    exit;
}
// ---------------------------

$response->send();

$kernel->terminate($request, $response);
