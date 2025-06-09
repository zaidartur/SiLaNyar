<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\HasilUji;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HasilUjiController extends Controller
{

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $idInstansi = $user->instansi()->pluck('id')->toArray();

        $hasil_uji = HasilUji::with([
            'pengujian.form_pengajuan.jenis_cairan',
            'pengujian.form_pengajuan.kategori.subkategori.parameter',
            'pengujian.form_pengajuan.kategori.parameter',
            'pengujian.form_pengajuan.instansi.user',
            'pengujian.user',
            'aduan',
        ])
            ->whereIn('status', ['proses_review', 'proses_peresmian', 'selesai'])
            ->whereNotNull('file_pdf')
            ->whereHas('pengujian.form_pengajuan', function ($query) use ($idInstansi) {
                $query->whereIn('id_instansi', $idInstansi);
            })
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

        if (!in_array($hasil_uji->status, ['proses_review', 'proses_peresmian', 'selesai']) || !$hasil_uji->file_pdf) {
            abort(433, 'Hasil Uji Belum Tersedia!');
        }

        $hasil_uji->load([
            'pengujian.form_pengajuan.kategori.parameter',
            'pengujian.form_pengajuan.kategori.subkategori.parameter',
            'pengujian.form_pengajuan.instansi.user',
            'pengujian.user',
        ]);

        $parameterKategori = $hasil_uji->pengujian->form_pengajuan->kategori->parameter->map(function ($param) {
            return [
                'id_parameter' => $param->id,
                'nama_parameter' => $param->nama_parameter,
                'satuan' => $param->satuan,
                'baku_mutu' => $param->pivot->baku_mutu ?? null,
            ];
        });

        $parameterSubKategori = $hasil_uji->pengujian->form_pengajuan->kategori->subkategori->flatMap(function ($sub) {
            return $sub->parameter->map(function ($param) {
                return [
                    'id_parameter' => $param->id,
                    'nama_parameter' => $param->nama_parameter,
                    'satuan' => $param->satuan,
                    'baku_mutu' => $param->pivot->baku_mutu ?? null,
                ];
            });
        });

        $semuaParameter = $parameterKategori->merge($parameterSubKategori)->keyBy('id_parameter');

        $parameterPengujian = DB::table('parameter_pengujian')
            ->where('id_pengujian', $hasil_uji->id_pengujian)
            ->get()
            ->map(function ($item) use ($semuaParameter) {
                $parameter = $semuaParameter[$item->id_parameter] ?? null;

                return [
                    'id_parameter' => $item->id_parameter,
                    'nama_parameter' => $parameter['nama_parameter'] ?? 'Tidak Ditemukan',
                    'satuan' => $parameter['satuan'] ?? null,
                    'nilai' => $item->nilai ?? null,
                    'baku_mutu' => $parameter['baku_mutu'] ?? null,
                    'keterangan' => $item->keterangan ?? null
                ];
            });

        return Inertia::render('customer/hasil_uji/Show', [
            'hasil_uji' => $hasil_uji,
            'parameter_pengujian' => $parameterPengujian,
        ]);
    }

    public function convert(HasilUji $hasil_uji)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $isCustomer = $user->role === 'customer';

        if (!$hasil_uji->file_pdf) {
            abort(433, 'File Hasil Uji Belum Tersedia!');
        }

        $hasil_uji->load([
            'pengujian.form_pengajuan.kategori.parameter',
            'pengujian.form_pengajuan.kategori.subkategori.parameter',
            'pengujian.form_pengajuan.instansi.user',
            'pengujian.user'
        ]);

        $pdf = PDF::loadView('hasil_uji.show', [
            'hasil_uji' => $hasil_uji,
            'tanggal' => now()->format('d-m-Y'),
            'is_customer' => $isCustomer,
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
