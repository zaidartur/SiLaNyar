<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FormPengajuan;
use App\Models\HasilUji;
use App\Models\Jadwal;
use App\Models\Pengujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class   DashboardController extends Controller
{
    public function index()
    {
        $pegawaiLogin = Auth::user();

        if ($pegawaiLogin->hasRole('superadmin')) {
            $customer = User::Role('customer');
            $pegawai = User::Role(['admin,teknisi,superadmin']);

            return Inertia::render('dashboard/SuperAdmin', [
                'customer' => $customer,
                'pegawai' => $pegawai
            ]);
        }

        if ($pegawaiLogin->hasRole('teknisi')) {
            $jadwalPengujian = Pengujian::where('id_user', $pegawaiLogin->id)->count();
            $jadwalPengambilan = Jadwal::where('id_user', $pegawaiLogin->id)->count();

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

        return Inertia::render('dashboard/Default');
    }
}
