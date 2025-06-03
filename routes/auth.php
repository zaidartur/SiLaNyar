<?php

use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\Settings\CustomerProfileController;
use App\Http\Controllers\Settings\PegawaiProfileController;
use App\Http\Controllers\Auth\SSOController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('sso/login', [SSOController::class, 'redirect'])->name('sso.login');
Route::get('sso/callback', [SSOController::class, 'callback'])->name('sso.callback');
Route::get('sso/logout', [SSOController::class, 'logout'])->name('sso.logout');

Route::prefix('pegawai')->middleware('auth:web')->group(function () {
    Route::get('dashboard', [PegawaiDashboardController::class, 'index'])->name('pegawai.dashboard');

    Route::get('sso/user', [PegawaiProfileController::class, 'user']);
    Route::get('profile/show', [PegawaiProfileController::class, 'show'])->name('pegawai.profile');
});

Route::prefix('customer')->middleware(['auth:web', 'role:customer'])->group(function () {
    Route::get('dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');

    //Fitur Profil
    Route::get('sso/user', [CustomerProfileController::class, 'user'])->name('sso.user');
    Route::get('profile/show', [CustomerProfileController::class, 'show'])->name('customer.profile');

    //fitur Instansi
    Route::get('profile/instansi/{instansi}', [CustomerProfileController::class, 'showInstansi'])->name('customer.profile.instansi.detail');
    Route::post('profile/instansi/store', [CustomerProfileController::class, 'storeInstansi']);
    Route::get('profile/instansi/edit/{instansi}', [CustomerProfileController::class, 'editInstansi']);
    Route::put('profile/instansi/{instansi}/edit', [CustomerProfileController::class, 'updateInstansi']);
});

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');
