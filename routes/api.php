<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SyncController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes (no authentication required)
Route::get('/test', function () {
    return response()->json(['message' => 'Backend is working!', 'status' => 'OK']);
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/send-email', [\App\Http\Controllers\MailController::class, 'send']);
    Route::post('/upgrade-plan', [\App\Http\Controllers\AuthController::class, 'upgradePlan']); // Mock assignment
    
    // Sync endpoint
    Route::post('/sync', [SyncController::class, 'sync']);
    
    // App Config & Feature Toggles
    Route::get('/app-features', [\App\Http\Controllers\AppSettingsController::class, 'getFeatures']);

    // AI Features
    Route::post('/ai/enhance-proposal', [\App\Http\Controllers\AiController::class, 'enhanceProposal']);
});

Route::post('/admin/notifications', [\App\Http\Controllers\AdminNotificationController::class, 'send']);
