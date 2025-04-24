<?php

use App\Http\Controllers\Auth\Customer\AuthenticatedSessionController as CustomerAuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\Pegawai\AuthenticatedSessionController as PegawaiAuthenticatedSessionController;
use App\Http\Controllers\Auth\Pegawai\RegisteredUserController as PegawaiRegisteredUserController;
use App\Http\Controllers\Auth\Customer\RegisteredUserController as CustomerRegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\VerifikasiAdminController;
use App\Http\Middleware\CheckVerifiedCustomer;
use App\Http\Middleware\CheckVerifiedPegawai;
use App\Models\Customer;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//autentikasi pegawai
Route::prefix('pegawai')->middleware('guest:pegawai')->group(function()
{
    Route::get('registrasi', [PegawaiRegisteredUserController::class, 'create'])->name('pegawai.registrasi');
    Route::post('registrasi', [PegawaiRegisteredUserController::class, 'store']);
    
    Route::get('login', [PegawaiAuthenticatedSessionController::class, 'create'])->name('pegawai.login');
    Route::post('login', [PegawaiAuthenticatedSessionController::class, 'store']);
});

Route::prefix('pegawai')->middleware('auth:pegawai', 'check.verified.pegawai')->group(function()
{
    Route::get('detail/{pegawai}', [VerifikasiAdminController::class, 'showPegawai']);
    Route::put('detail/{id}', [VerifikasiAdminController::class, 'verifikasiPegawai']);

    Route::get('detail/customer/{customer}', [VerifikasiAdminController::class, 'showCustomer']);
    Route::put('detail/customer/{id}', [VerifikasiAdminController::class, 'verifikasiCustomer']);

    Route::post('logout', [PegawaiAuthenticatedSessionController::class, 'destroy'])->name('pegawai.logout');    
});

//autentikasi customer
Route::middleware('guest:customer')->group(function()
{
    Route::get('registrasi', [CustomerRegisteredUserController::class, 'create'])->name('registrasi');
    Route::post('registrasi', [CustomerRegisteredUserController::class, 'store']);
    
    Route::get('login', [CustomerAuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [CustomerAuthenticatedSessionController::class, 'store']);
});

Route::middleware(['auth:customer', 'check.verified.customer'])->group(function() {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::post('logout', [CustomerAuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Route::middleware('guest')->group(function () {
//     Route::get('register', [RegisteredUserController::class, 'create'])
//         ->name('register');

//     Route::post('register', [RegisteredUserController::class, 'store']);

//     Route::get('login', [AuthenticatedSessionController::class, 'create'])
//         ->name('login');

//     Route::post('login', [AuthenticatedSessionController::class, 'store']);

//     Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
//         ->name('password.request');

//     Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
//         ->name('password.email');

//     Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
//         ->name('password.reset');

//     Route::post('reset-password', [NewPasswordController::class, 'store'])
//         ->name('password.store');
// });

// Route::middleware('auth')->group(function () {
//     Route::get('verify-email', EmailVerificationPromptController::class)
//         ->name('verification.notice');

//     Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
//         ->middleware(['signed', 'throttle:6,1'])
//         ->name('verification.verify');

//     Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//         ->middleware('throttle:6,1')
//         ->name('verification.send');

//     Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
//         ->name('password.confirm');

//     Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

//     Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
//         ->name('logout');
// });
