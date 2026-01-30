<?php

define('LARAVEL_START', microtime(true));

// Adjust path if your public folder is different or if index.php is in root
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
} elseif (file_exists(__DIR__.'/vendor/autoload.php')) {
    // Handling case where this file is in root
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
} else {
    die("Could not find autoload.php. Please make sure this file is in your public folder or root.");
}

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Artisan;

echo "<div style='font-family:sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; background: #f4f4f4; border-radius: 8px;'>";
echo "<h1>🛠 Freelancy Repair Tool</h1>";

try {
    echo "<h3>1. Clearing Optimization Cache...</h3>";
    Artisan::call('optimize:clear');
    echo "<pre style='background:#000; color:#0f0; padding:10px; border-radius:5px;'>" . Artisan::output() . "</pre>";

    echo "<h3>2. Clearing Route Cache...</h3>";
    Artisan::call('route:clear');
    echo "<pre style='background:#000; color:#0f0; padding:10px; border-radius:5px;'>" . Artisan::output() . "</pre>";

    echo "<h3>3. Re-caching Routes...</h3>";
    Artisan::call('route:cache');
    echo "<pre style='background:#000; color:#0f0; padding:10px; border-radius:5px;'>" . Artisan::output() . "</pre>";
    
    echo "<h2 style='color:green'>✅ Fix Complete!</h2>";
    echo "<p>The 'Route not defined' error happens when the server remembers old routes. We have just forced it to learn the new ones.</p>";
    echo "<p><b>Please delete this file (fix-cache.php) after use for security.</b></p>";
    echo "<a href='/admin' style='background: #3b82f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Go to Admin Panel -></a>";
    
} catch (\Exception $e) {
    echo "<h2 style='color:red'>❌ Error</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}

echo "</div>";
