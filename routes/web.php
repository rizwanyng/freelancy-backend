<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Client Portal - Public Invoice View
Route::get('/portal/invoice/{id}', [\App\Http\Controllers\PublicInvoiceController::class, 'show'])->name('public.invoice');

// Fallback login route for Filament/Auth middleware
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');
