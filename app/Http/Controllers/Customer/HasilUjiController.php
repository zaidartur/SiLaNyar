<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\HasilUji;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class HasilUjiController extends Controller
{

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $idInstansi = $user->instansi()->pluck('id')->toArray();

        $hasil_uji = HasilUji::with(['parameter', 'pengujian.form_pengajuan', 'aduan'])
            ->whereIn('status', ['acc', 'proses_review', 'proses_peresmian', 'selesai'])
            ->whereNotNull('file_pdf')
            ->whereHas('pengujian.form_pengajuan', function ($query) use ($idInstansi) {
                $query->whereIn('id_instansi', $idInstansi);
            })
            ->has('aduan')
            ->get();

        return Inertia::render('customer/hasil_uji/Index', [
            'hasil_uji' => $hasil_uji
        ]);
    }

    public function show(HasilUji $hasil_uji)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($hasil_uji->pengujian->form_pengajuan->instansi->id_user !== $user->id) {
            abort(403, 'Anda Tidak Memiliki Akses Di Halaman Ini');
        }

        if (!$hasil_uji->status === ['acc', 'proses_review', 'proses_peresmian', 'selesai'] || !$hasil_uji->file_pdf) {
            abort(433, 'Hasil Uji Belum Tersedia!');
        }

        $hasil_uji->load('parameter', 'pengujian')
            ->whereIn('status', ['acc', 'proses_review', 'proses_peresmian', 'selesai'])
            ->whereNotNull('file_pdf')
            ->has('aduan');


        return Inertia::render('customer/hasil_uji/Show', [
            'hasil_uji' => $hasil_uji
        ]);
    }

    public function convert(HasilUji $hasil_uji)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($hasil_uji->pengujian->form_pengajuan->instansi->id_user !== $user->id) {
            abort(403, 'Anda Tidak Memiliki Akses Di Halaman Ini');
        }

        if (!$hasil_uji->status === 'acc') {
            abort(433, 'File Hasil Uji Belum Tersedia!');
        }

        $hasil_uji->load(['parameter', 'pengujian.form_pengajuan.instansi.user', 'pengujian.user']);

        $pdf = PDF::loadView('hasil_uji.show', [
            'hasil_uji' => $hasil_uji,
            'tanggal' => now()->format('d-m-Y')
        ]);

        return $pdf->download('Hasil_Uji_' . $hasil_uji->id . '.pdf');
    }

    public function verifikasi(HasilUji $hasil_uji, Request $request)
    {
        $request->validate([
            'status' => 'required|in:proses_peresmian'
        ]);

        if ($hasil_uji->status !== 'proses_review') {
            return Redirect::back()->withErrors([
                'status' => 'Status Hasil Uji Anda Saat Ini Adalah ' . $hasil_uji->status . '. Status Tersebut Tidak Dapat Dirubah Oleh Customer!'
            ]);
        }

        $hasil_uji->update([
            'status' => $request->status
        ]);

        return Redirect::route('customer.hasil_uji.index')->with('message', 'Anda Telah Memasuki Fase Proses Peresmian');
    }
}
