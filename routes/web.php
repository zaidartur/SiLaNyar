<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\sampelController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//route user
//pengajuan
Route::get('pengajuan/daftar', [PengajuanController::class, 'register']);
Route::post('pengajuan/store', [PengajuanController::class, 'store']);


//route admin

//crud kategori
Route::get('kategori/',[KategoriController::class, 'index']);
Route::get('kategori/create', [KategoriController::class, 'create']);
Route::post('kategori/store', [KategoriController::class, 'store']);
Route::get('kategori/{kategori}/edit', [KategoriController::class, 'edit']);
Route::put('kategori/edit/{kategori}', [KategoriController::class, 'update']);
Route::post('kategori/{id}',[KategoriController::class, 'destroy']);

//crud sampel
Route::get('sampel/', [sampelController::class, 'index']);

//pengajuan
Route::get('pengajuan/',[PengajuanController::class, 'index']);
Route::post('pengajuan/{id}/verifikasi',[PengajuanController::class, 'verification']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
