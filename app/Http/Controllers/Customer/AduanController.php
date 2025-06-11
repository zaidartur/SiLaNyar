<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\AduanMasukNotification;
use App\Models\Aduan;
use App\Models\HasilUji;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AduanController extends Controller
{
    // public function index()
    // {
    //     $aduan = Aduan::with('hasil_uji.pengujian.form_pengajuan.instansi.user')->get();
        
    //     return Inertia::render('customer/aduan/Index', [
    //         'aduan' => $aduan
    //     ]);
    // }

    public function create(HasilUji $hasil_uji)
    {
        $user = Auth::user();

        if ($hasil_uji->pengujian->form_pengajuan->instansi->id_user !== $user->id) {
            abort(403, 'Anda Tidak Memiliki Akses Di Halaman Ini!');
        }

        return Inertia::render('customer/aduan/Tambah', [
            'hasil_uji' => $hasil_uji,
        ]);
    }

    public function store(HasilUji $hasil_uji, Request $request)
    {
        $user = Auth::user();

        if ($hasil_uji->pengujian->form_pengajuan->instansi->id_user !== $user->id) {
            abort(403, 'Anda Tidak Memiliki Akses Di Aduan Ini!');
        }

        $request->validate([
            'masalah' => 'required|string',
            'perbaikan' => 'required|string',
            'terkait' => 'nullable|in:administrasi,pengujian',
        ]);

        Aduan::create([
            'id_hasil_uji' => $hasil_uji->id,
            'id_user' => $user->id,
            'masalah' => $request->masalah,
            'perbaikan' => $request->perbaikan,
            'status' => 'diproses'
        ]);

        return Redirect::route('customer.hasil_uji.index')->with('message', 'Aduan Berhasil Terkirim');
    }
}