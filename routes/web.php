<?php

use App\Http\Controllers\Customer\AduanController;
use App\Http\Controllers\Customer\HasilUjiController as CustomerHasilUjiController;
use App\Http\Controllers\Customer\JadwalController as CustomerJadwalController;
use App\Http\Controllers\Customer\JadwalPengantaranController;
use App\Http\Controllers\Customer\PembayaranController;
use App\Http\Controllers\Customer\PengajuanController as CustomerPengajuanController;
use App\Http\Controllers\HasilUjiController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\JenisCairanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\Pegawai\AdminPengajuanController;
use App\Http\Controllers\Pegawai\PegawaiController;
use App\Http\Controllers\Pegawai\PelangganController;
use App\Http\Controllers\Pegawai\PembayaranController as PegawaiPembayaranController;
use App\Http\Controllers\Pegawai\VerifikasiController;
use App\Http\Controllers\PengujianController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Mail\SendOtpMail;

Route::get('/test-hasiluji', function () {
    $customer = new \App\Models\Customer(['nama' => 'Budi']);
    $kategori = new \App\Models\Kategori(['nama' => 'Air Limbah']);
    $form = new \App\Models\FormPengajuan(['jenis_cairan' => 'Air Sungai']);
    $form->setRelation('customer', $customer);
    $form->setRelation('kategori', $kategori);

    $pegawai = new \App\Models\Pegawai(['nama' => 'Teknisi A']);
    $pengujian = new \App\Models\Pengujian([
        'tanggal_uji' => now(),
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:00',
    ]);
    $pengujian->setRelation('form_pengajuan', $form);
    $pengujian->setRelation('pegawai', $pegawai);

    $hasilUji = new \App\Models\HasilUji(['id' => 1]);
    $hasilUji->id = 1;
    $hasilUji->setRelation('pengujian', $pengujian);

    return view('email.hasiluji', [
        'hasil_uji' => $hasilUji
    ]);
});

Route::get('/test-otp', function () {
    $otp = rand(100000, 999999);
    $nama = 'Aji';
    
    return view('email.otp', ['otp' => $otp, 'nama' => $nama]);
});

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//route superadmin
Route::prefix('superadmin')->middleware(['auth:pegawai'])->group(function () {

    //fitur permission
    Route::middleware(['check.permission:kelola-permission'])->group(function () {
        Route::get('permission', [PermissionController::class, 'index'])->name('superadmin.permission.index');
        Route::get('permission/create', [PermissionController::class, 'create']);
        Route::post('permission/store', [PermissionController::class, 'store']);
        Route::get('permission/edit/{permission}', [PermissionController::class, 'edit']);
        Route::put('permission/{permission}/edit', [PermissionController::class, 'update']);
        Route::delete('permission/{id}', [PermissionController::class, 'destroy']);
    });

    //fitur role
    Route::middleware(['check.permission:kelola-role'])->group(function () {
        Route::get('role', [RoleController::class, 'index'])->name('superadmin.role.index');
        Route::get('role/create', [RoleController::class, 'create']);
        Route::post('role/store', [RoleController::class, 'store']);
        Route::get('role/edit/{role}', [RoleController::class, 'edit']);
        Route::post('role/{role}/edit', [RoleController::class, 'update']);
        Route::delete('role/{id}', [RoleController::class, 'destroy']);
    });

    //fitur verifikasi pegawai
    Route::get('pegawai', [PegawaiController::class, 'index'])->middleware('check.permission:lihat-pegawai')->name('superadmin.pegawai.index');
    Route::get('pegawai/{pegawai}', [PegawaiController::class, 'show'])->middleware('check.permission:detail-pegawai');
    Route::post('verifikasi/{id}', [VerifikasiController::class, 'verifikasiPegawai'])->middleware('check.permission:verifikasi-pegawai');
});

//route user
Route::prefix('customer')->middleware(['auth:customer', 'check.verified.customer'])->group(function () {

    //fitur jadwal
    Route::get('pengujian', [CustomerJadwalController::class, 'index'])->name('customer.jadwal.index');
    Route::get('pengujian/{id}', [CustomerJadwalController::class, 'show']);

    //fitur pengajuan
    Route::get('pengajuan', [CustomerPengajuanController::class, 'index'])->name('customer.pengajuan.index');
    Route::get('pengajuan/daftar', [CustomerPengajuanController::class, 'daftar'])->name('customer.pengajuan.daftar');
    Route::post('pengajuan/store', [CustomerPengajuanController::class, 'store'])->name('customer.pengajuan.store');
    Route::get('pengajuan/{id}', [CustomerPengajuanController::class, 'show'])->name('customer.pengajuan.show');

    //fitur pembayaran
    Route::get('pembayaran/{id}', [PembayaranController::class, 'show'])->name('customer.pembayaran.show');
    Route::get('pembayaran/upload/{id}', [PembayaranController::class, 'upload'])->name('customer.pembayaran.upload');
    Route::post('pembayaran/{id}', [PembayaranController::class, 'process']);
    Route::get('pembayaran/{id}/sukses', [PembayaranController::class, 'sukses'])->name('customer.pembayaran.sukses');

    //fitur hasil uji
    Route::get('hasiluji', [CustomerHasilUjiController::class, 'index'])->name('customer.hasil_uji.index');
    Route::get('hasiluji/{hasil_uji}', [CustomerHasilUjiController::class, 'show'])->name('customer.hasil_uji.detail');
    Route::get('hasiluji/{hasil_uji}/PDF', [CustomerHasilUjiController::class, 'convert'])->name('customer.hasil_uji.convert');
    Route::get('hasiluji/{hasil_uji}/PDF/Elektronik', [CustomerHasilUjiController::class, 'convertTandaTanganBasah'])->name('customer.hasil_uji.convert');

    //fitur aduan
    Route::get('hasiluji/aduan/{hasil_uji}', [AduanController::class, 'create']);
    Route::post('hasiluji/aduan/{hasil_uji}', [AduanController::class, 'store']);

    //fitur antar
    Route::get('antar/', [JadwalPengantaranController::class, 'index'])->name('customer.pengantaran.index');
    Route::get('antar/edit/{jadwal}', [JadwalPengantaranController::class, 'edit']);
    Route::put('antar/{jadwa}/edit', [JadwalPengantaranController::class, 'update']);
    Route::delete('antar/{id}', [JadwalPengantaranController::class, 'destroy']);
});

//route pegawai
Route::prefix('pegawai')->middleware(['auth:pegawai'])->group(function () {

    //fitur pengajuan
    Route::get('pengajuan', [AdminPengajuanController::class, 'index'])->middleware('check.permission:lihat-pengajuan')->name('pegawai.pengajuan.index');
    Route::get('pengajuan/{id}', [AdminPengajuanController::class, 'show'])->middleware('check.permission:detail-pengajuan')->name('pegawai.pengajuan.show');
    Route::get('pengajuan/edit/{pengajuan}', [AdminPengajuanController::class, 'edit'])->middleware('check.permission:edit-pengajuan')->name('pegawai.pengajuan.edit');
    Route::put('pengajuan/{id}/edit', [AdminPengajuanController::class, 'update'])->name('pegawai.pengajuan.update');

    //fitur pembayaran
    Route::get('pembayaran', [PegawaiPembayaranController::class, 'index'])->middleware('check.permission:lihat-pembayaran')->name('pegawai.pembayaran.index');
    Route::get('pembayaran/{id}', [PegawaiPembayaranController::class, 'show'])->middleware('check.permission:detail-pembayaran')->name('pegawai.pembayaran.show');
    Route::get('pembayaran/edit/{id}', [PegawaiPembayaranController::class, 'edit'])->middleware('check.permission:edit-pembayaran')->name('pegawai.pembayaran.edit');
    Route::put('pembayaran/{pembayaran}/edit', [PegawaiPembayaranController::class, 'update']);

    //fitur pengujian
    Route::get('pengujian/', [PengujianController::class, 'index'])->middleware('check.permission:lihat-pengujian')->name('pegawai.pengujian.index');
    Route::get('pengujian/create', [PengujianController::class, 'create'])->middleware('check.permission:tambah-pengujian');
    Route::post('pengujian/store', [PengujianController::class, 'store']);
    Route::get('pengujian/edit/{pengujian}', [PengujianController::class, 'edit'])->middleware('check.permission:edit-pengujian');
    Route::put('pengujian/{pengujian}/edit', [PengujianController::class, 'update']);
    Route::get('pengujian/{pengujian}', [PengujianController::class, 'show'])->middleware('check.permission:detail-pengujian');
    Route::delete('pengujian/{id}', [PengujianController::class, 'destroy'])->middleware('check.permission:delete-pengujian');

    //fitur pengambilan/pengantaran
    Route::get('jadwal/', [JadwalController::class, 'index'])->middleware('check.permission:lihat-pengambilan')->name('pegawai.pengambilan.index');
    Route::get('jadwal/create', [JadwalController::class, 'create'])->middleware('check.permission:tambah-pengambilan');
    Route::post('jadwal/store', [JadwalController::class, 'store']);
    Route::get('jadwal/{jadwal}/edit', [JadwalController::class, 'edit'])->middleware('check.permission:edit-pengambilan');
    Route::put('jadwal/edit/{jadwal}', [JadwalController::class, 'update']);
    Route::get('jadwal/{jadwal}', [JadwalController::class, 'show'])->middleware('check.permission:detail-pengambilan');
    Route::delete('jadwal/{id}', [JadwalController::class, 'destroy'])->middleware('check.permission:delete-pengambilan');

    //fitur jenis cairan
    Route::get('jenis_cairan', [JenisCairanController::class, 'index'])->middleware('check.permission:lihat-jenis_sampel')->name('pegawai.jenis_cairan.index');
    Route::get('jenis_cairan/create', [JenisCairanController::class, 'create'])->middleware('check.permission:tambah-jenis_sampel');
    Route::post('jenis_cairan/store', [JenisCairanController::class, 'store']);
    Route::get('jenis_cairan/edit/{jenis_cairan}', [JenisCairanController::class, 'edit'])->middleware('check.permission:edit-jenis_sampel');
    Route::put('jenis_cairan/{jenis_cairan}/edit', [JenisCairanController::class, 'update']);
    Route::delete('jenis_cairan/{id}', [JenisCairanController::class, 'destroy'])->middleware('check.permission:delete-jenis_sampel');

    //fitur kategori
    Route::get('kategori/', [KategoriController::class, 'index'])->middleware('check.permission:lihat-kategori')->name('pegawai.kategori.index');
    Route::get('kategori/create', [KategoriController::class, 'create'])->middleware('check.permission:tambah-kategori');
    Route::post('kategori/store', [KategoriController::class, 'store']);
    Route::get('kategori/{kategori}/edit', [KategoriController::class, 'edit'])->middleware('check.permission:edit-kategori');
    Route::put('kategori/edit/{kategori}', [KategoriController::class, 'update']);
    Route::delete('kategori/{id}', [KategoriController::class, 'destroy'])->middleware('check.permission:delete-kategori');

    //fitur parameter
    Route::get('parameter/', [ParameterController::class, 'index'])->middleware('check.permission:lihat-parameter')->name('pegawai.parameter.index');
    Route::get('parameter/create', [ParameterController::class, 'create'])->middleware('check.permission:tambah-parameter');
    Route::post('parameter/store', [ParameterController::class, 'store']);
    Route::get('parameter/edit/{parameter}', [ParameterController::class, 'edit'])->middleware('check.permission:edit-parameter');
    Route::put('parameter/{parameter}/edit', [ParameterController::class, 'update']);
    Route::delete('parameter/{id}', [ParameterController::class, 'destroy'])->middleware('check.permission:delete-parameter');

    //fitur hasil uji
    Route::get('hasiluji/', [HasilUjiController::class, 'index'])->middleware('check.permission:lihat-hasil_uji')->name('pegawai.hasil_uji.index');
    Route::get('hasiluji/create', [HasilUjiController::class, 'create'])->middleware('check.permission:tambah-hasil_uji');
    Route::post('hasiluji/store', [HasilUjiController::class, 'store']);
    Route::get('hasiluji/edit/{hasil_uji}', [HasilUjiController::class, 'edit'])->middleware('check.permission:edit-hasil_uji');
    Route::put('hasiluji/{hasil_uji}/edit', [HasilUjiController::class, 'update']);
    Route::get('hasiluji/{hasil_uji}', [HasilUjiController::class, 'show'])->middleware('check.permission:detail-hasil_uji');
    Route::get('hasiluji/riwayat/{id}', [HasilUjiController::class, 'riwayat'])->middleware('check.permission:riwayat-hasil_uji');
    Route::delete('hasiluji/{id}', [HasilUjiController::class, 'destroy'])->middleware('check.permission:delete-hasil_uji');

    //fitur pelanggan
    Route::get('pelanggan', [PelangganController::class, 'index'])->middleware('check.permission:lihat-pelanggan')->name('pegawai.pelanggan.index');
    Route::get('pelanggan/{customer}', [PelangganController::class, 'show'])->middleware('check.permission:detail-pelanggan');

    //fitur verifikasi customer
    Route::post('pelanggan/verifikasi/{id}', [VerifikasiController::class, 'verifikasiCustomer'])->middleware('check.permission:verifikasi-customer');
});

Route::post('midtrans/callback', [PembayaranController::class, 'callback'])->name('midtrans.callback');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
