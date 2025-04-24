<?php
use App\Http\Controllers\Auth\Customer\AuthenticatedSessionController;
use App\Http\Controllers\Auth\Customer\RegisteredUserController;
use App\Http\Controllers\HasilUjiController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PengujianController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Middleware\CheckVerifiedCustomer;
use App\Http\Middleware\CheckPermission;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/about-us', function () {
    return Inertia::render('AboutUs');
})->name('about-us');

Route::get('/informasi', function () {
    return Inertia::render('Informasi');
})->name('informasi');

//route superadmin

Route::prefix('superadmin')->middleware(['auth:pegawai'])->group(function()
{
    //crud permission
   Route::middleware(['check.permission:kelola-permission'])->group(function()
   {
        Route::get('permission', [PermissionController::class, 'index'])->name('permission.index');
        Route::get('permission/create', [PermissionController::class, 'create']);
        Route::post('permission/store', [PermissionController::class, 'store']);
        Route::get('permission/edit/{permission}', [PermissionController::class, 'edit']);
        Route::put('permission/{permission}/edit', [PermissionController::class, 'update']);
        Route::delete('permission/{id}', [PermissionController::class, 'destroy']);
   });

   //crud role
   Route::middleware(['check.permission:kelola-role'])->group(function()
   {
        Route::get('role', [RoleController::class, 'index'])->name('role.index');
        Route::get('role/create', [RoleController::class, 'create']);
        Route::post('role/store', [RoleController::class, 'store']);
        Route::get('role/edit/{role}', [RoleController::class, 'edit']);
        Route::post('role/{role}/edit', [RoleController::class, 'update']);
        Route::delete('role/{id}', [RoleController::class, 'destroy']);
   });
});

//route user

//pengajuan
Route::get('pengajuan/daftar', [PengajuanController::class, 'register']);
Route::post('pengajuan/store', [PengajuanController::class, 'store']);

//route pegawai

//crud parameter
Route::get('parameter/', [ParameterController::class, 'index'])->name('parameter.index');
Route::get('parameter/create', [ParameterController::class, 'create']);
Route::post('parameter/store', [ParameterController::class, 'store']);
Route::get('parameter/edit/{parameter}', [ParameterController::class, 'edit']);
Route::put('parameter/{parameter}/edit', [ParameterController::class, 'update']);
Route::delete('parameter/{id}', [ParameterController::class, 'destroy']);

//crud kategori
Route::get('kategori/',[KategoriController::class, 'index'])->name('kategori.index');
Route::get('kategori/create', [KategoriController::class, 'create']);
Route::post('kategori/store', [KategoriController::class, 'store']);
Route::get('kategori/{kategori}/edit', [KategoriController::class, 'edit']);
Route::put('kategori/edit/{kategori}', [KategoriController::class, 'update']);
Route::get('kategori/{kategori}', [KategoriController::class, 'show']);
Route::delete('kategori/{id}',[KategoriController::class, 'destroy']);

//crud pengujian
Route::get('pengujian/', [PengujianController::class, 'index'])->name('pengujian.index');
Route::get('pengujian/create', [PengujianController::class, 'create']);
Route::post('pengujian/store', [PengujianController::class, 'store']);
Route::get('pengujian/edit/{pengujian}', [PengujianController::class, 'edit']);
Route::put('pengujian/{pengujian}/edit', [PengujianController::class, 'update']);
Route::get('pengujian/{pengujian}', [PengujianController::class, 'show']);
Route::delete('pengujian/{id}',[PengujianController::class, 'destroy']);

//crud hasil uji
Route::get('hasiluji/', [HasilUjiController::class, 'index'])->name('hasil_uji.index');
Route::get('hasiluji/create', [HasilUjiController::class, 'create']);
Route::post('hasiluji/store', [HasilUjiController::class, 'store']);
Route::get('hasiluji/edit/{hasil_uji}', [HasilUjiController::class, 'edit']);
Route::put('hasiluji/{hasil_uji}/edit', [HasilUjiController::class, 'update']);
Route::get('hasiluji/{hasil_uji}', [HasilUjiController::class, 'show']);
Route::delete('hasiluji/{id}', [HasilUjiController::class, 'destroy']);

//crud jadwal
Route::get('jadwal/', [JadwalController::class, 'index'])->name('jadwal.index');
Route::get('jadwal/create', [JadwalController::class, 'create']);
Route::post('jadwal/store', [JadwalController::class, 'store']);
Route::get('jadwal/{jadwal}/edit', [JadwalController::class, 'edit']);
Route::put('jadwal/edit/{jadwal}', [JadwalController::class, 'update']);
Route::delete('jadwal/{id}', [JadwalController::class, 'destroy']);

// Route for test dashboard
Route::get('/test', function () {
    return view('test.dashboard');
});

// Test routes for Kategori
Route::prefix('test/kategori')->group(function () {
    Route::get('/', function () {
        $kategori = \App\Models\kategori::latest()->get();
        return view('test.kategori.index', compact('kategori'));
    })->name('test.kategori.index');
    
    Route::get('/create', function () {
        return view('test.kategori.create');
    });
    
    Route::get('/{kategori}/edit', function ($id) {
        $kategori = \App\Models\kategori::findOrFail($id);
        return view('test.kategori.edit', compact('kategori'));
    });
});

// Test routes for Jadwal
Route::prefix('test/jadwal')->group(function () {
    Route::get('/', function () {
        $jadwal = \App\Models\jadwal::latest()->get();
        $form_pengajuan = \App\Models\form_pengajuan::latest()->get();
        return view('test.jadwal.index', compact('jadwal', 'form_pengajuan'));
    })->name('test.jadwal.index');
    
    Route::get('/create', function () {
        $form_pengajuan = \App\Models\form_pengajuan::latest()->get();
        return view('test.jadwal.create', compact('form_pengajuan'));
    });
    
    Route::get('/{jadwal}/edit', function ($id) {
        $jadwal = \App\Models\jadwal::findOrFail($id);
        $form_pengajuan = \App\Models\form_pengajuan::latest()->get();
        return view('test.jadwal.edit', compact('jadwal', 'form_pengajuan'));
    });
});

//pengajuan
Route::get('pengajuan/',[PengajuanController::class, 'index']);
Route::post('pengajuan/{id}/verifikasi',[PengajuanController::class, 'verification']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
