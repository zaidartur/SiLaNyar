<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\form_pengajuan;
use App\Models\hasil_uji;
use App\Models\jadwal;
use App\Models\pegawai;
use App\Models\pengujian;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $pegawaiLogin = auth('pegawai')->user;

        if ($pegawaiLogin->hasRole('superadmin')) {
            $customer = Customer::all();
            $pegawai = pegawai::all();

            return Inertia::render('dashboard/superadmin', [
                'customer' => $customer,
                'pegawai' => $pegawai
            ]);
        }

        if ($pegawaiLogin->hasRole('teknisi')) {
            $jadwalPengujian = pengujian::where('id_pegawai', $pegawaiLogin)->count();
            $jadwalPengambilan = jadwal::where('id_pegawai', $pegawaiLogin)->count();

            $pengajuan = form_pengajuan::all();
            $pengambilan = jadwal::all();

            return Inertia::render('dashboard/teknisi', [
                'statistik' => [
                    'jadwalPengujian' => $jadwalPengujian,
                    'jadwalPengambilan' => $jadwalPengambilan,
                ],
                'pengajuan' => $pengajuan,
                'pengambilan' => $pengambilan
            ]);
        }

        if ($pegawaiLogin->hasRole('admin')) {
            $pengajuan = form_pengajuan::count();
            $jadwal = jadwal::count();
            $pengujian = pengujian::count();
            $hasil_uji = hasil_uji::count();

            return Inertia::render('dashboard/admin', [
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
}
