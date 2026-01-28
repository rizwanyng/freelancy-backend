<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('email', 'rizzpathan2@gmail.com')->first();
if ($user) {
    $user->password = \Illuminate\Support\Facades\Hash::make('12345678');
    $user->save();
    echo "Password updated successfully!\n";
} else {
    echo "User not found\n";
}
