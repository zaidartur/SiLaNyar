<?php

use App\Http\Controllers\Auth\Customer\AuthenticatedSessionController as CustomerAuthenticatedSessionController;
use App\Http\Controllers\Auth\Pegawai\AuthenticatedSessionController as PegawaiAuthenticatedSessionController;
use App\Http\Controllers\Auth\Pegawai\RegisteredUserController as PegawaiRegisteredUserController;
use App\Http\Controllers\Auth\Customer\RegisteredUserController as CustomerRegisteredUserController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Pegawai\DashboardController;
use App\Http\Controllers\Settings\CustomerProfileController;
use App\Http\Controllers\Settings\CustomerResetPasswordController;
use App\Http\Controllers\Settings\PegawaiProfileController;
use App\Http\Controllers\Settings\PegawaiResetPassword;
use App\Http\Controllers\Auth\SSOController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('sso/login', [SSOController::class, 'redirect'])->name('sso.login');
Route::get('sso/callback', [SSOController::class, 'callback'])->name('sso.callback');
    Route::get('sso/logout', [SSOController::class, 'logout'])->name('sso.logout');

//autentikasi pegawai
Route::prefix('pegawai')->middleware('guest:pegawai')->group(function () {
    Route::get('login', [PegawaiAuthenticatedSessionController::class, 'create'])->name('pegawai.login');
    Route::post('login', [PegawaiAuthenticatedSessionController::class, 'store']);

    Route::get('lupapassword', [PegawaiResetPassword::class, 'lihatForm'])->name('pegawai.password.lupa');
    Route::post('lupapassword', [PegawaiResetPassword::class, 'kirimOtp']);
    Route::get('verifikasiotp', [PegawaiResetPassword::class, 'lihatOtpForm'])->name('pegawai.password.reset');
    Route::post('verifikasiotp', [PegawaiResetPassword::class, 'verifikasiOtp']);
    Route::post('gantipassword', [PegawaiResetPassword::class, 'gantiPassword']);
});
Route::get('admin/dashboard', [DashboardController::class, 'indexTest'])->name('pegawai.dashboard');

Route::prefix('pegawai')->middleware('auth:pegawai')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('pegawai.dashboard');

    Route::get('profile/show', [PegawaiProfileController::class, 'show'])->name('pegawai.profile');
    Route::get('profile/edit', [PegawaiProfileController::class, 'edit']);
    Route::put('profile/update', [PegawaiProfileController::class, 'update']);
    Route::delete('profile/destroy', [PegawaiProfileController::class, 'destroy'])->name('pegawai.profile.destroy');
    Route::post('logout', [PegawaiAuthenticatedSessionController::class, 'destroy'])->name('pegawai.logout');
});

Route::middleware('guest:customer')->group(function () {
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

Route::prefix('customer')->middleware(['auth:customer'])->group(function () {
    Route::get('dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');

    //Fitur Profil
    Route::get('sso/user', [CustomerProfileController::class, 'user'])->name('sso.user');
    Route::get('profile/show', [CustomerProfileController::class, 'show'])->name('customer.profile');
    Route::get('profile/edit', [CustomerProfileController::class, 'edit']);
    Route::put('profile/update', [CustomerProfileController::class, 'update']);
    Route::delete('profile/destroy', [CustomerProfileController::class, 'destroy'])->name('customer.profile.destroy');

    //fitur Instansi
    Route::get('profile/instansi/{instansi}', [CustomerProfileController::class, 'showInstansi'])->name('customer.profile.instansi.detail');
    Route::post('profile/instansi/store', [CustomerProfileController::class, 'storeInstansi']);
    Route::get('profile/instansi/edit/{instansi}', [CustomerProfileController::class, 'editInstansi']);
    Route::put('profile/instansi/{instansi}/edit', [CustomerProfileController::class, 'updateInstansi']);

});

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');
