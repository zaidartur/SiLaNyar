<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\FormPengajuan;
use App\Models\HasilUji;
use App\Models\Jadwal;
use App\Models\Pegawai;
use App\Models\Pengujian;
use Illuminate\Http\Request;
use Inertia\Inertia;

class   DashboardController extends Controller
{
    public function index()
    {
        $pegawaiLogin = auth('pegawai')->user;

        if ($pegawaiLogin->hasRole('superadmin')) {
            $customer = Customer::all();
            $pegawai = Pegawai::all();

            return Inertia::render('dashboard/SuperAdmin', [
                'customer' => $customer,
                'pegawai' => $pegawai
            ]);
        }

        if ($pegawaiLogin->hasRole('teknisi')) {
            $jadwalPengujian = Pengujian::where('id_pegawai', $pegawaiLogin)->count();
            $jadwalPengambilan = Jadwal::where('id_pegawai', $pegawaiLogin)->count();

            $pengajuan = FormPengajuan::all();
            $pengambilan = Jadwal::all();

            return Inertia::render('dashboard/Teknisi', [
                'statistik' => [
                    'jadwalPengujian' => $jadwalPengujian,
                    'jadwalPengambilan' => $jadwalPengambilan,
                ],
                'pengajuan' => $pengajuan,
                'pengambilan' => $pengambilan
            ]);
        }

        if ($pegawaiLogin->hasRole('admin')) {
            $pengajuan = FormPengajuan::count();
            $jadwal = Jadwal::count();
            $pengujian = Pengujian::count();
            $hasil_uji = HasilUji::count();

            return Inertia::render('pegawai/Dashboard', [
                'statistik' => [
                    'pengajuan' => $pengajuan,
                    'jadwal' => $jadwal,
                    'pengujian' => $pengujian,
                    'hasil_uji' => $hasil_uji,
                ],
            ]);
        }

        return Inertia::render('dashboard/default');
    }

    public function indexTest()
    {
        return Inertia::render('pegawai/Dashboard');
    }
}
