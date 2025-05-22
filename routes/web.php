<?php
use App\Http\Controllers\Customer\AduanController;
use App\Http\Controllers\Customer\HasilUjiController as CustomerHasilUjiController;
use App\Http\Controllers\Customer\JadwalController as CustomerJadwalController;
use App\Http\Controllers\Customer\PembayaranController as CustomerPembayaranController;
use App\Http\Controllers\Customer\PengajuanController as CustomerPengajuanController;
use App\Http\Controllers\Pegawai\HasilUjiController as PegawaiHasilUjiController;
use App\Http\Controllers\Pegawai\JadwalController as PegawaiJadwalController;
use App\Http\Controllers\Pegawai\JenisCairanController;
use App\Http\Controllers\Pegawai\KategoriController;
use App\Http\Controllers\Pegawai\ParameterController;
use App\Http\Controllers\Pegawai\PengajuanController as PegawaiPengajuanController;
use App\Http\Controllers\Pegawai\PembayaranController as PegawaiPembayaranController;
use App\Http\Controllers\Pegawai\VerifikasiAduanController;
use App\Http\Controllers\Pegawai\VerifikasiInstansiController;
use App\Http\Controllers\Pegawai\PengujianController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/test-hasiluji', function () {
    $customer = new \App\Models\User(['nama' => 'Budi']);
    $kategori = new \App\Models\Kategori(['nama' => 'Air Limbah']);
    $form = new \App\Models\FormPengajuan(['jenis_cairan' => 'Air Sungai']);
    $form->setRelation('customer', $customer);
    $form->setRelation('kategori', $kategori);

    $pegawai = new \App\Models\User(['nama' => 'Teknisi A']);
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
})->name('dashboard');

//route superadmin
Route::prefix('superadmin')->middleware(['auth:web', 'role:superadmin'])->group(function () {

    //fitur permission
    Route::middleware(['check.permission:kelola_permission'])->group(function () {
        Route::get('permission', [PermissionController::class, 'index'])->name('superadmin.permission.index');
        Route::get('permission/create', [PermissionController::class, 'create']);
        Route::post('permission/store', [PermissionController::class, 'store']);
        Route::get('permission/edit/{permission}', [PermissionController::class, 'edit']);
        Route::put('permission/{permission}/edit', [PermissionController::class, 'update']);
        Route::delete('permission/{id}', [PermissionController::class, 'destroy']);
    });

    //fitur role
    Route::middleware(['check.permission:kelola_role'])->group(function () {
        Route::get('role', [RoleController::class, 'index'])->name('superadmin.role.index');
        Route::get('role/create', [RoleController::class, 'create']);
        Route::post('role/store', [RoleController::class, 'store']);
        Route::get('role/edit/{role}', [RoleController::class, 'edit'])->name('superadmin.role.edit');
        Route::put('role/{role}/edit', [RoleController::class, 'update']);
        Route::delete('role/{id}', [RoleController::class, 'destroy']);
    });

    //fitur user
    Route::middleware(['check.permission:kelola_user'])->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('pegawai.index');
        Route::post('users/{user}/sync-roles', [UserController::class, 'syncRoles'])->name('superadmin.users.syncRoles');
    });
});

//route customer
Route::prefix('customer')->middleware(['auth:web', 'role:customer'])->group(function () {

    //fitur jadwal
    Route::get('pengujian', [CustomerJadwalController::class, 'index'])->name('customer.jadwal.index');
    Route::get('pengujian/{id}', [CustomerJadwalController::class, 'show']);

    //fitur pengajuan
    Route::get('pengajuan', [CustomerPengajuanController::class, 'index'])->name('customer.pengajuan.index');
    Route::get('pengajuan/daftar', [CustomerPengajuanController::class, 'daftar'])->name('customer.pengajuan.daftar');
    Route::post('pengajuan/store', [CustomerPengajuanController::class, 'store'])->name('customer.pengajuan.store');
    Route::get('pengajuan/{id}', [CustomerPengajuanController::class, 'show'])->name('customer.pengajuan.detail');

    //fitur pembayaran
    Route::get('pembayaran', [CustomerPembayaranController::class, 'index'])->name('customer.pembayaran.index');
    Route::get('pembayaran/{id}', [CustomerPembayaranController::class, 'show'])->name('customer.pembayaran.detail');
    Route::get('pembayaran/upload/{id}', [CustomerPembayaranController::class, 'upload'])->name('customer.pembayaran.upload');
    Route::post('pembayaran/{id}', [CustomerPembayaranController::class, 'process']);
    Route::get('pembayaran/{id}/sukses', [CustomerPembayaranController::class, 'sukses'])->name('customer.pembayaran.sukses');

    //fitur hasil uji
    Route::get('hasiluji', [CustomerHasilUjiController::class, 'index'])->name('customer.hasil_uji.index');
    Route::get('hasiluji/{hasil_uji}', [CustomerHasilUjiController::class, 'show'])->name('customer.hasil_uji.detail');
    Route::get('hasiluji/{hasil_uji}/PDF', [CustomerHasilUjiController::class, 'convert'])->name('customer.hasil_uji.convert');

    //fitur aduan
    Route::get('hasiluji/aduan/{hasil_uji}', [AduanController::class, 'create']);
    Route::post('hasiluji/aduan/{hasil_uji}', [AduanController::class, 'store']);
});

//route pegawai
Route::prefix('pegawai')->group(function () {
    //fitur instansi
    Route::middleware('check.permission:kelola_instansi')->group(function () {
        Route::get('instansi', [VerifikasiInstansiController::class, 'index'])->name('pegawai.instansi.index');
        Route::get('instansi/edit/{instansi}', [VerifikasiInstansiController::class, 'edit']);
        Route::put('instansi/{id}/edit', [VerifikasiInstansiController::class, 'verifikasi']);
        Route::get('instansi/{instansi}', [VerifikasiInstansiController::class, 'show']);
    });

    //fitur pengajuan
    Route::get('pengajuan', [PegawaiPengajuanController::class, 'index'])->middleware('check.permission:lihat-pengajuan')->name('pegawai.pengajuan.index');
    Route::get('pengajuan/{id}', [PegawaiPengajuanController::class, 'show'])->middleware('check.permission:detail-pengajuan')->name('pegawai.pengajuan.detail');
    Route::get('pengajuan/edit/{pengajuan}', [PegawaiPengajuanController::class, 'edit'])->middleware('check.permission:edit-pengajuan')->name('pegawai.pengajuan.edit');
    Route::put('pengajuan/{id}/edit', [PegawaiPengajuanController::class, 'update'])->name('pegawai.pengajuan.update');

    //fitur pembayaran
    Route::middleware('check.permission:kelola_pembayaran')->group(function () {
        Route::get('pembayaran', [PegawaiPembayaranController::class, 'index'])->name('pegawai.pembayaran.index');
        Route::get('pembayaran/{id}', [PegawaiPembayaranController::class, 'show'])->name('pegawai.pembayaran.detail');
        Route::get('pembayaran/edit/{id}', [PegawaiPembayaranController::class, 'edit'])->name('pegawai.pembayaran.edit');
        Route::put('pembayaran/{pembayaran}/edit', [PegawaiPembayaranController::class, 'update']);
    });

    //fitur pengujian
    Route::middleware('check.permission:kelola_pengujian')->group(function () {
        Route::get('pengujian/', [PengujianController::class, 'index'])->name('pegawai.pengujian.index');
        Route::get('pengujian/create', [PengujianController::class, 'create']);
        Route::post('pengujian/store', [PengujianController::class, 'store']);
        Route::get('pengujian/edit/{pengujian}', [PengujianController::class, 'edit']);
        Route::put('pengujian/{pengujian}/edit', [PengujianController::class, 'update']);
        Route::get('pengujian/{pengujian}', [PengujianController::class, 'show']);
        Route::delete('pengujian/{id}', [PengujianController::class, 'destroy']);
    });

    //fitur pengambilan
    Route::get('pengambilan/', [PegawaiJadwalController::class, 'index'])->middleware('check.permission:lihat-pengambilan')->name('pegawai.pengambilan.index');
    Route::get('pengambilan/create', [PegawaiJadwalController::class, 'create'])->middleware('check.permission:tambah-pengambilan');
    Route::post('pengambilan/store', [PegawaiJadwalController::class, 'store']);
    Route::get('pengambilan/{jadwal}/edit', [PegawaiJadwalController::class, 'edit'])->middleware('check.permission:edit-pengambilan');
    Route::put('pengambilan/edit/{jadwal}', [PegawaiJadwalController::class, 'update']);
    Route::get('pengambilan/{jadwal}', [PegawaiJadwalController::class, 'show'])->middleware('check.permission:detail-pengambilan');
    Route::delete('pengambilan/{id}', [PegawaiJadwalController::class, 'destroy'])->middleware('check.permission:delete-pengambilan');

    //fitur jenis cairan
    Route::middleware('check.permission:kelola_jenis_cairan')->group(function () {
        Route::get('jenis_cairan', [JenisCairanController::class, 'index'])->name('pegawai.jenis_cairan.index');
        Route::get('jenis_cairan/create', [JenisCairanController::class, 'create']);
        Route::post('jenis_cairan/store', [JenisCairanController::class, 'store']);
        Route::get('jenis_cairan/edit/{jenis_cairan}', [JenisCairanController::class, 'edit']);
        Route::put('jenis_cairan/{jenis_cairan}/edit', [JenisCairanController::class, 'update']);
        Route::delete('jenis_cairan/{id}', [JenisCairanController::class, 'destroy']);
    });

    //fitur kategori
    Route::middleware('check.permission:kelola_kategori')->group(function () {
        Route::get('kategori/', [KategoriController::class, 'index'])->name('pegawai.kategori.index');
        Route::get('kategori/create', [KategoriController::class, 'create']);
        Route::post('kategori/store', [KategoriController::class, 'store']);
        Route::get('kategori/{kategori}/edit', [KategoriController::class, 'edit']);
        Route::put('kategori/edit/{kategori}', [KategoriController::class, 'update']);
        Route::delete('kategori/{id}', [KategoriController::class, 'destroy']);
    });

    //fitur parameter
    Route::middleware('check.permission:kelola_parameter')->group(function () {
        Route::get('parameter/', [ParameterController::class, 'index'])->name('pegawai.parameter.index');
        Route::get('parameter/create', [ParameterController::class, 'create'])->name('pegawai.parameter.tambah');
        Route::post('parameter/store', [ParameterController::class, 'store']);
        Route::get('parameter/edit/{parameter}', [ParameterController::class, 'edit'])->name('pegawai.parameter.edit');
        Route::put('parameter/{parameter}/edit', [ParameterController::class, 'update']);
        Route::delete('parameter/{id}', [ParameterController::class, 'destroy'])->name('pegawai.parameter.destroy');
    });

    //fitur hasil uji
    Route::get('hasiluji/', [PegawaiHasilUjiController::class, 'index'])->middleware('check.permission:lihat-hasil_uji')->name('pegawai.hasil_uji.index');
    Route::get('hasiluji/create', [PegawaiHasilUjiController::class, 'create'])->middleware('check.permission:tambah-hasil_uji');
    Route::post('hasiluji/store', [PegawaiHasilUjiController::class, 'store']);
    Route::get('hasiluji/edit/{hasil_uji}', [PegawaiHasilUjiController::class, 'edit'])->middleware('check.permission:edit-hasil_uji');
    Route::put('hasiluji/{hasil_uji}/edit', [PegawaiHasilUjiController::class, 'update']);
    Route::get('hasiluji/{hasil_uji}', [PegawaiHasilUjiController::class, 'show'])->middleware('check.permission:detail-hasil_uji');
    Route::get('hasiluji/riwayat/{id}', [PegawaiHasilUjiController::class, 'riwayat'])->middleware('check.permission:riwayat-hasil_uji');
    Route::delete('hasiluji/{id}', [PegawaiHasilUjiController::class, 'destroy'])->middleware('check.permission:delete-hasil_uji');

    //fitur Aduan
    Route::middleware('check.permission:kelola_aduan')->group(function () {
        Route::get('aduan/', [VerifikasiAduanController::class, 'index'])->name('pegawai.aduan.index');
        Route::get('aduan/{aduan}', [VerifikasiAduanController::class, 'show'])->name('pegawai.aduan.detail');
        Route::put('aduan/verifikasi/{id}', [VerifikasiAduanController::class, 'verifikasi']);
    });
});

require __DIR__ . '/auth.php';
