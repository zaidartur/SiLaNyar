<?php

use App\Http\Controllers\Customer\AduanController;
use App\Http\Controllers\Customer\HasilUjiController as CustomerHasilUjiController;
use App\Http\Controllers\Customer\JadwalController as CustomerJadwalController;
use App\Http\Controllers\Customer\PembayaranController as CustomerPembayaranController;
use App\Http\Controllers\Customer\PengajuanController as CustomerPengajuanController;
use App\Http\Controllers\Pegawai\HasilUjiController as PegawaiHasilUjiController;
use App\Http\Controllers\Pegawai\HasilUjiHistoriController;
use App\Http\Controllers\Pegawai\JadwalController as PegawaiJadwalController;
use App\Http\Controllers\Pegawai\JenisCairanController;
use App\Http\Controllers\Pegawai\KategoriController;
use App\Http\Controllers\Pegawai\LaporanKeuanganController;
use App\Http\Controllers\Pegawai\ParameterController;
use App\Http\Controllers\Pegawai\SubKategoriController;
use App\Http\Controllers\Pegawai\PengajuanController as PegawaiPengajuanController;
use App\Http\Controllers\Pegawai\PembayaranController as PegawaiPembayaranController;
use App\Http\Controllers\Pegawai\VerifikasiAduanController;
use App\Http\Controllers\Pegawai\PengujianController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

if (app()->environment('local')) {
    Route::get('/dev-login', function () {
        $user = \App\Models\User::find(3);

        if (!$user) {
            abort(404, 'User not found');
        }

        Auth::loginUsingId($user->id);

        return redirect('/dashboard');
    });
}

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('hasiluji/{hasil_uji}/PDF', [CustomerHasilUjiController::class, 'convert'])->name('hasil_uji.convert');

//route superadmin
Route::prefix('superadmin')->middleware(['auth:web'])->group(function () {

    //fitur permission
    Route::middleware(['check.permission:kelola permission'])->group(function () {
        Route::get('permission', [PermissionController::class, 'index'])->name('superadmin.permission.index');
        Route::get('permission/create', [PermissionController::class, 'create']);
        Route::post('permission/store', [PermissionController::class, 'store']);
        Route::get('permission/edit/{permission}', [PermissionController::class, 'edit']);
        Route::put('permission/{permission}/edit', [PermissionController::class, 'update']);
        Route::delete('permission/{id}', [PermissionController::class, 'destroy']);
    });

    //fitur role
    Route::middleware(['check.permission:kelola role'])->group(function () {
        Route::get('role', [RoleController::class, 'index'])->name('superadmin.role.index');
        Route::get('role/create', [RoleController::class, 'create']);
        Route::post('role/store', [RoleController::class, 'store']);
        Route::get('role/edit/{role}', [RoleController::class, 'edit'])->name('superadmin.role.edit');
        Route::put('role/{role}/edit', [RoleController::class, 'update']);
        Route::delete('role/{id}', [RoleController::class, 'destroy']);
    });

    //fitur user
    Route::middleware(['check.permission:kelola user'])->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('pegawai.index');
        Route::post('users/{user}/sync-roles', [UserController::class, 'syncRoles'])->name('superadmin.users.syncRoles');
    });
});

//route customer
Route::prefix('customer')->middleware(['auth:web', 'role:customer'])->group(function () {

    //fitur jadwal
    Route::get('jadwal', [CustomerJadwalController::class, 'index'])->name('customer.jadwal.index');
    Route::get('jadwal/penjemputan', [CustomerJadwalController::class, 'penjemputan'])->name('customer.jadwal.penjemputan');
    Route::get('jadwal/pengantaran', [CustomerJadwalController::class, 'pengantaran'])->name('customer.jadwal.pengantaran');
    Route::get('jadwal/{id}', [CustomerJadwalController::class, 'show'])->name('customer.jadwal.detail');

    //fitur pengajuan
    Route::get('pengajuan', [CustomerPengajuanController::class, 'index'])->name('customer.pengajuan.index');
    Route::post('pengajuan/store', [CustomerPengajuanController::class, 'store'])->name('customer.pengajuan.store');
    Route::get('pengajuan/{id}', [CustomerPengajuanController::class, 'show'])->name('customer.pengajuan.detail');
    Route::get('pengajuan/edit/{pengajuan}', [CustomerPengajuanController::class, 'edit'])->name('customer.pengajuan.edit');
    Route::put('pengajuan/{pengajuan}/edit', [CustomerPengajuanController::class, 'update'])->name('customer.pengajuan.update');
    Route::delete('pengajuan/{id}', [CustomerPengajuanController::class, 'destroy'])->name('customer.pengajuan.delete');

    //fitur pembayaran
    Route::get('pembayaran/{id}', [CustomerPembayaranController::class, 'show'])->name('customer.pembayaran.show');
    Route::get('pembayaran/upload/{id}', [CustomerPembayaranController::class, 'upload'])->name('customer.pembayaran.upload');
    Route::post('pembayaran/{id}', [CustomerPembayaranController::class, 'process'])->name('customer.pembayaran.process');
    Route::get('pembayaran/{id}/sukses', [CustomerPembayaranController::class, 'sukses'])->name('customer.pembayaran.sukses');

    //fitur hasil uji
    Route::get('hasiluji', [CustomerHasilUjiController::class, 'index'])->name('customer.hasil_uji.index');
    Route::get('hasiluji/{hasil_uji}', [CustomerHasilUjiController::class, 'show'])->name('customer.hasil_uji.detail');

    //fitur aduan
    Route::get('hasiluji/aduan/{hasil_uji}', [AduanController::class, 'create'])->name('customer.aduan.create');
    Route::post('hasiluji/aduan/{hasil_uji}', [AduanController::class, 'store'])->name('customer.aduan.store');
    Route::put('hasiluji/{hasil_uji}/verifikasi', [CustomerHasilUjiController::class, 'verifikasi']);
});

//route pegawai
Route::prefix('pegawai')->group(function () {
    //fitur pengajuan
    Route::get('pengajuan', [PegawaiPengajuanController::class, 'index'])->middleware('check.permission:lihat pengajuan')->name('pegawai.pengajuan.index');
    Route::get('pengajuan/create', [PegawaiPengajuanController::class, 'create'])->middleware('check.permission:tambah pengajuan')->name('pegawai.pengajuan.create');
    Route::get('pengajuan/store', [PegawaiPengajuanController::class, 'store'])->middleware('check.permission:tambah pengajuan');
    Route::get('pengajuan/{id}', [PegawaiPengajuanController::class, 'show'])->middleware('check.permission:detail pengajuan')->name('pegawai.pengajuan.detail');
    Route::get('pengajuan/edit/{pengajuan}', [PegawaiPengajuanController::class, 'edit'])->middleware('check.permission:edit pengajuan')->name('pegawai.pengajuan.edit');
    Route::put('pengajuan/{id}/edit', [PegawaiPengajuanController::class, 'update'])->middleware('check.permission:edit pengajuan')->name('pegawai.pengajuan.update');
    Route::put('pengajuan/{id}/edit-parameter-kategori', [PegawaiPengajuanController::class, 'updateKategoriParameter'])->middleware('check.permission:edit pengajuan')->name('pegawai.pengajuan.update');
    Route::delete('pengajuan/{pengajuan}', [PegawaiPengajuanController::class, 'destroy'])->middleware('check.permission:hapus pengajuan')->name('pegawai.pengajuan.destroy');

    //fitur pembayaran
    Route::middleware('check.permission:kelola pembayaran')->group(function () {
        Route::get('pembayaran', [PegawaiPembayaranController::class, 'index'])->name('pegawai.pembayaran.index');
        Route::get('pembayaran/{id}', [PegawaiPembayaranController::class, 'show'])->name('pegawai.pembayaran.detail');
        Route::get('pembayaran/edit/{id}', [PegawaiPembayaranController::class, 'edit'])->name('pegawai.pembayaran.edit');
        Route::put('pembayaran/{pembayaran}/edit', [PegawaiPembayaranController::class, 'update']);
    });

    //fitur pengujian
    Route::get('pengujian/', [PengujianController::class, 'index'])->middleware('check.permission:lihat pengujian')->name('pegawai.pengujian.index');
    Route::get('pengujian/create', [PengujianController::class, 'create'])->middleware('check.permission:tambah pengujian')->name('pegawai.pengujian.create');
    Route::post('pengujian/store', [PengujianController::class, 'store'])->middleware('check.permission:tambah pengujian');
    Route::get('pengujian/edit/{pengujian}', [PengujianController::class, 'edit'])->middleware('check.permission:edit pengujian')->name('pegawai.pengujian.edit');
    Route::put('pengujian/{pengujian}/edit', [PengujianController::class, 'update'])->middleware('check.permission:edit pengujian');
    Route::put('pengujian/verifikasi/{id}', [PengujianController::class, 'verifikasi'])->middleware('check.permission:edit pengujian');
    Route::get('pengujian/{pengujian}', [PengujianController::class, 'show'])->middleware('check.permission:detail pengujian')->name('pegawai.pengujian.detail');
    Route::delete('pengujian/{id}', [PengujianController::class, 'destroy'])->middleware('check.permission:hapus pengujian')->name('pegawai.pengujian.destroy');

    //fitur pengambilan
    Route::get('pengambilan/', [PegawaiJadwalController::class, 'index'])->middleware('check.permission:lihat pengambilan')->name('pegawai.pengambilan.index');
    Route::get('pengambilan/create', [PegawaiJadwalController::class, 'create'])->middleware('check.permission:tambah pengambilan')->name('pegawai.pengambilan.create');
    Route::post('pengambilan/store', [PegawaiJadwalController::class, 'store'])->middleware('check.permission:tambah pengambilan');
    Route::get('pengambilan/edit/{jadwal}', [PegawaiJadwalController::class, 'edit'])->middleware('check.permission:edit pengambilan')->name('pegawai.pengambilan.edit');
    Route::put('pengambilan/{jadwal}/edit', [PegawaiJadwalController::class, 'update'])->middleware('check.permission:edit pengambilan');
    Route::get('pengambilan/{jadwal}', [PegawaiJadwalController::class, 'show'])->middleware('check.permission:detail pengambilan')->name('pegawai.pengambilan.detail');
    Route::delete('pengambilan/{id}', [PegawaiJadwalController::class, 'destroy'])->middleware('check.permission:hapus pengambilan')->name('pegawai.pengambilan.destroy');

    //fitur jenis cairan
    Route::middleware('check.permission:kelola jenis cairan')->group(function () {
        Route::get('jenis-cairan', [JenisCairanController::class, 'index'])->name('pegawai.jenis_cairan.index');
        Route::post('jenis-cairan/store', [JenisCairanController::class, 'store']);
        Route::put('jenis-cairan/{jenis_cairan}/edit', [JenisCairanController::class, 'update']);
        Route::delete('jenis-cairan/{id}', [JenisCairanController::class, 'destroy']);
    });

    //fitur kategori
    Route::middleware('check.permission:kelola kategori')->group(function () {
        Route::get('kategori/', [KategoriController::class, 'index'])->name('pegawai.kategori.index');
        Route::get('kategori/create', [KategoriController::class, 'create'])->name('pegawai.kategori.tambah');
        Route::post('kategori/store', [KategoriController::class, 'store']);
        Route::get('kategori/edit/{id}', [KategoriController::class, 'edit'])->name('pegawai.kategori.edit');
        Route::put('kategori/{kategori}/edit', [KategoriController::class, 'update']);
        Route::get('kategori/{id}', [KategoriController::class, 'show'])->name('pegawai.kategori.detail');
        Route::delete('kategori/{id}', [KategoriController::class, 'destroy'])->name('pegawai.kategori.destroy');
    });

    //fitur subkategori
    Route::middleware('check.permission:kelola subkategori')->group(function () {
        Route::get('subkategori/', [SubKategoriController::class, 'index'])->name('pegawai.subkategori.index');
        Route::get('subkategori/create', [SubKategoriController::class, 'create'])->name('pegawai.subkategori.tambah');
        Route::post('subkategori/store', [SubKategoriController::class, 'store']);
        Route::get('subkategori/edit/{id}', [SubKategoriController::class, 'edit'])->name('pegawai.subkategori.edit');
        Route::put('subkategori/{subkategori}/edit', [SubKategoriController::class, 'update']);
        Route::get('subkategori/{id}', [SubKategoriController::class, 'show'])->name('pegawai.subkategori.detail');
        Route::delete('subkategori/{id}', [SubKategoriController::class, 'destroy'])->name('pegawai.subkategori.destroy');
    });

    //fitur parameter
    Route::middleware('check.permission:kelola parameter')->group(function () {
        Route::get('parameter/', [ParameterController::class, 'index'])->name('pegawai.parameter.index');
        Route::get('parameter/create', [ParameterController::class, 'create'])->name('pegawai.parameter.tambah');
        Route::post('parameter/store', [ParameterController::class, 'store']);
        // Route::get('parameter/edit/{parameter}', [ParameterController::class, 'edit'])->name('pegawai.parameter.edit');
        Route::put('parameter/{parameter}/edit', [ParameterController::class, 'update']);
        Route::delete('parameter/{id}', [ParameterController::class, 'destroy'])->name('pegawai.parameter.destroy');
    });

    //fitur Laporan Keuangan
    Route::get('laporan-keuangan', [LaporanKeuanganController::class, 'index'])->middleware('check.permission:laporan keuangan')->name('pegawai.laporan_keuangan.index');

    //fitur hasil uji
    Route::get('hasiluji/', [PegawaiHasilUjiController::class, 'index'])->middleware('check.permission:lihat hasil uji')->name('pegawai.hasil_uji.index');
    Route::get('hasiluji/create', [PegawaiHasilUjiController::class, 'create'])->middleware('check.permission:tambah hasil uji')->name('pegawai.hasil_uji.tambah');
    Route::post('hasiluji/store', [PegawaiHasilUjiController::class, 'store'])->middleware('check.permission:tambah hasil uji');
    Route::get('hasiluji/edit/{id}', [PegawaiHasilUjiController::class, 'edit'])->middleware('check.permission:edit hasil uji')->name('pegawai.hasil_uji.edit');
    Route::put('hasiluji/{hasil_uji}/edit', [PegawaiHasilUjiController::class, 'update'])->middleware('check.permission:edit hasil uji')->name('pegawai.hasil_uji.update');
    Route::put('hasiluji/verifikasi/{id}', [PegawaiHasilUjiController::class, 'verifikasi'])->middleware('check.permission:edit status hasil uji');
    Route::get('hasiluji/{id}', [PegawaiHasilUjiController::class, 'show'])->middleware('check.permission:detail hasil uji')->name('pegawai.hasil_uji.detail');
    Route::get('hasiluji/riwayat/{id}', [HasilUjiHistoriController::class, 'index'])->middleware('check.permission:riwayat hasil uji')->name('pegawai.hasil_uji.riwayat');
    Route::get('hasiluji/riwayat/show/{id}', [HasilUjiHistoriController::class, 'show'])->middleware('check.permission:riwayat hasil uji')->name('pegawai.hasil_uji.riwayat.show');
    Route::delete('hasiluji/{id}', [PegawaiHasilUjiController::class, 'destroy'])->middleware('check.permission:hapus hasil uji')->name('pegawai.hasil_uji.destroy');

    //fitur Aduan
    Route::middleware('check.permission:kelola aduan')->group(function () {
        Route::get('aduan/', [VerifikasiAduanController::class, 'index'])->name('pegawai.aduan.index');
        Route::get('aduan/{aduan}', [VerifikasiAduanController::class, 'show'])->name('pegawai.aduan.detail');
        Route::put('aduan/verifikasi/{id}', [VerifikasiAduanController::class, 'verifikasi']);
    });
});

require __DIR__ . '/auth.php';