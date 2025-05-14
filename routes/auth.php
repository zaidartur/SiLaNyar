<?php

use App\Http\Controllers\Auth\Customer\AuthenticatedSessionController as CustomerAuthenticatedSessionController;
use App\Http\Controllers\Auth\Pegawai\AuthenticatedSessionController as PegawaiAuthenticatedSessionController;
use App\Http\Controllers\Auth\Pegawai\RegisteredUserController as PegawaiRegisteredUserController;
use App\Http\Controllers\Auth\Customer\RegisteredUserController as CustomerRegisteredUserController;
use App\Http\Controllers\auth\SSOAuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Pegawai\DashboardController;
use App\Http\Controllers\Settings\CustomerProfileController;
use App\Http\Controllers\Settings\CustomerResetPasswordController;
use App\Http\Controllers\Settings\PegawaiProfileController;
use App\Http\Controllers\Settings\PegawaiResetPassword;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//autentikasi pegawai
Route::prefix('pegawai')->middleware('guest:pegawai')->group(function()
{
    Route::get('login', [PegawaiAuthenticatedSessionController::class, 'create'])->name('pegawai.login');
    Route::post('login', [PegawaiAuthenticatedSessionController::class, 'store']);

    Route::get('lupapassword', [PegawaiResetPassword::class, 'lihatForm'])->name('pegawai.password.lupa');
    Route::post('lupapassword', [PegawaiResetPassword::class, 'kirimOtp']);
    Route::get('verifikasiotp', [PegawaiResetPassword::class, 'lihatOtpForm'])->name('pegawai.password.reset');
    Route::post('verifikasiotp', [PegawaiResetPassword::class, 'verifikasiOtp']);
    Route::post('gantipassword', [PegawaiResetPassword::class, 'gantiPassword']);
});
Route::get('admin/dashboard', [DashboardController::class, 'indexNoAuthent'])->name('pegawai.dashboard');
Route::prefix('pegawai')->middleware('auth:pegawai')->group(function()
{
    Route::get('dashboard', [DashboardController::class, 'index'])->name('pegawai.dashboard');

    Route::get('registrasi', [PegawaiRegisteredUserController::class, 'create'])->name('pegawai.registrasi');
    Route::post('registrasi', [PegawaiRegisteredUserController::class, 'store']);
    
    Route::get('profile/show', [PegawaiProfileController::class, 'show'])->name('pegawai.profile');
    Route::get('profile/edit', [PegawaiProfileController::class, 'edit']);
    Route::put('profile/update', [PegawaiProfileController::class, 'update']);
    Route::delete('profile/destroy', [PegawaiProfileController::class, 'destroy'])->name('pegawai.profile.destroy');
    Route::post('logout', [PegawaiAuthenticatedSessionController::class, 'destroy'])->name('pegawai.logout');    
});

//autentikasi customer
Route::prefix('customer/sso')->group(function()
{
   Route::get('/redirect', [SSOAuthController::class, 'redirect'])->name('customer.sso.redirect');
   Route::get('/callback', [SSOAuthController::class, 'callback'])->name('customer.sso.callback');
   Route::get('/logout', [SSOAuthController::class, 'logout'])->name('customer.sso.logout'); 
});

Route::middleware('guest:customer')->group(function()
{
    Route::get('registrasi', [CustomerRegisteredUserController::class, 'create'])->name('customer.registrasi');
    Route::post('registrasi', [CustomerRegisteredUserController::class, 'store']);
    
    Route::get('login', [CustomerAuthenticatedSessionController::class, 'create'])->name('customer.login');
    Route::post('login', [CustomerAuthenticatedSessionController::class, 'store']);

    Route::get('lupapassword', [CustomerResetPasswordController::class, 'lihatForm'])->name('customer.password.lupa');
    Route::post('lupapassword', [CustomerResetPasswordController::class, 'kirimOtp']);
    Route::get('verifikasiotp', [CustomerResetPasswordController::class, 'lihatOtpForm'])->name('customer.password.reset');
    Route::post('verifikasiotp', [CustomerResetPasswordController::class, 'verifikasiOtp']);
    Route::post('gantipassword', [CustomerResetPasswordController::class, 'gantiPassword']);
});

Route::prefix('customer')->middleware(['auth:customer', 'check.verified.customer'])->group(function() {
    Route::get('dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');

    //Fitur Profil
    Route::get('profile/show', [CustomerProfileController::class, 'show'])->name('customer.profile');
    Route::get('profile/edit', [CustomerProfileController::class, 'edit']);
    Route::put('profile/update', [CustomerProfileController::class, 'update']);
    Route::delete('profile/destroy', [CustomerProfileController::class, 'destroy'])->name('customer.profile.destroy');

    //Fitur Instansi
    Route::post('profile/instansi', [CustomerProfileController::class, 'storeOrUpdateInstansi']);
    

    Route::post('logout', [CustomerAuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');
