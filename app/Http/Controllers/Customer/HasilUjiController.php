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
        $hasil_uji = HasilUji::with(['parameter', 'pengujian.form_pengajuan'])
            ->where('status', 'acc')
            ->whereNotNull('file_pdf')
            ->whereHas('pengujian.form_pengajuan', function ($query) {
                $query->where('id_user', Auth::id());
            })
            ->get();

        return Inertia::render('customer/hasil_uji/Index', [
            'hasil_uji' => $hasil_uji
        ]);
    }

    public function show(HasilUji $hasil_uji)
    {
        if ($hasil_uji->pengujian->form_pengajuan->id_user !== Auth::id()) {
            abort(403, 'Anda Tidak Memiliki Akses Di Halaman Ini');
        }

        if (!$hasil_uji->status === 'acc' || !$hasil_uji->file_pdf) {
            abort(433, 'Hasil Uji Belum Tersedia!');
        }

        $hasil_uji->load('parameter', 'pengujian');


        return Inertia::render('customer/hasil_uji/Show', [
            'hasil_uji' => $hasil_uji
        ]);
    }

    public function convert(HasilUji $hasil_uji)
    {
        if ($hasil_uji->pengujian->form_pengajuan->id_user !== Auth::id()) {
            abort(403, 'Anda Tidak Memiliki Akses Di Halaman Ini');
        }

        if (!$hasil_uji->status === 'acc' || !$hasil_uji->file_pdf) {
            abort(433, 'File Hasil Uji Belum Tersedia!');
        }

        $pdfPath = public_path('uploads/hasil_uji/' . $hasil_uji->file_pdf);

        if (!file_exists($pdfPath)) {
            abort(433, 'File PDF Tidak Ditemukan!');
        }

        return response()->file($pdfPath);
    }

    public function convertTandaTanganBasah(HasilUji $hasil_uji)
    {
        if ($hasil_uji->pengujian->form_pengajuan->id_user !== Auth::id()) {
            abort(403, 'Anda Tidak Memiliki Akses Di Halaman Ini');
        }

        if (!$hasil_uji->status === 'acc') {
            abort(433, 'File Hasil Uji Belum Tersedia!');
        }

        $hasil_uji->load('parameter', 'pengujian.form_pengajuan.customer');

        $pdf = PDF::loadView('hasil_uji.show', [
            'hasil_uji' => $hasil_uji,
            'tanggal' => now()->format('d-m-Y')
        ]);

        return $pdf->download('Hasil_Uji_' . $hasil_uji->id . '.pdf');
    }
}
