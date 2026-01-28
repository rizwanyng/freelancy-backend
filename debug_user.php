<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('email', 'rizzpathan2@gmail.com')->first();

if ($user) {
    echo "User found: " . $user->email . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Password Hash: " . substr($user->password, 0, 20) . "...\n";
    echo "Has canAccessFilament: " . (method_exists($user, 'canAccessFilament') ? 'Yes' : 'No') . "\n";
    echo "Can access Filament: " . ($user->canAccessFilament() ? 'Yes' : 'No') . "\n";
    
    // Test password
    $check = \Illuminate\Support\Facades\Hash::check('12345678', $user->password);
    echo "Password match test: " . ($check ? 'PASS' : 'FAIL') . "\n";
} else {
    echo "User not found\n";
    echo "Total users: " . \App\Models\User::count() . "\n";
}
