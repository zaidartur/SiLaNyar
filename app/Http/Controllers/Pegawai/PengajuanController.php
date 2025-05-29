<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormPengajuan;
use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PengajuanController extends Controller
{
    private function hitungTotalBiaya(FormPengajuan $pengajuan)
    {
        $kategori = $pengajuan->kategori;
        $parameterDipilih = $pengajuan->parameter;
        $parameterKategori = $kategori->parameter;

        if ($parameterDipilih->count() == $parameterKategori->count() && $parameterDipilih->pluck('id')->diff($parameterKategori->pluck('id')->isEmpty())) {
            return $kategori->harga;
        } else {
            return $parameterDipilih->sum('harga');
        }
    }

    //lihat daftar pengajuan dari pegawai
    public function index()
    {
        $pengajuan = FormPengajuan::with(['kategori', 'parameter', 'instansi.user', 'jenis_cairan'])
            ->orderByDesc('updated_at')
            ->get();

        return Inertia::render('pegawai/pengajuan/Index', [
            'pengajuan' => $pengajuan,
            'filter' => request()->all()
        ]);
    }

    //lihat detail pengajuan dari pegawai
    public function show($id)
    {
        $pengajuan = FormPengajuan::with(['kategori', 'parameter', 'instansi.user', 'jenis_cairan'])
            ->where('id', $id)
            ->firstOrFail();

        return Inertia::render('pegawai/pengajuan/Detail', [
            'pengajuan' => $pengajuan
        ]);
    }

    //edit pengajuan dari pegawai
    public function edit(FormPengajuan $pengajuan)
    {
        $pengajuan->load(['kategori', 'parameter', 'instansi.user', 'jenis_cairan']);

        $kategoriList = Kategori::with('parameter', 'subkategori.parameter')->select('id', 'nama')->get();
        $parameterList = ParameterUji::select('id', 'nama_parameter')->get();

        return Inertia::render('pegawai/pengajuan/Edit', [
            'pengajuan' => $pengajuan,
            'kategoriList' => $kategoriList,
            'parameterList' => $parameterList
        ]);
    }

    //update pengajuan dari pegawai
    public function update($id, Request $request)
    {
        try {
            $pengajuan = FormPengajuan::with(['kategori', 'parameter', 'instansi.user'])->findOrFail($id);

            $rules = [
                'status_pengajuan' => 'required|in:diterima,ditolak'
            ];

            if ($pengajuan->metode_pengambilan === 'diantar') {
                $rules['id_kategori'] = 'required|exists:kategori,id';
                $rules['parameter'] = 'required|array';
                $rules['parameter.*'] = 'exists:parameter_uji,id';
                $rules['metode_pembayaran'] = 'required|in:tunai,transfer';
            }

            $validated = $request->validate($rules);

            $pengajuan->status_pengajuan = $validated['status_pengajuan'];

            if ($pengajuan->metode_pengambilan === 'diantar') {
                $pengajuan->id_kategori = $validated['id_kategori'];

                $pengajuan->parameter()->sync($validated['parameter']);
            }

            $pengajuan->save();

            if ($pengajuan->metode_pengambilan === 'diantar') {
                Pembayaran::createOrUpdate([
                    'id_order' => 'INV-' . strtoupper(Str::random(10)) . '-' . time(),
                    'id_form_pengajuan' => $pengajuan->id,
                    'total_biaya' => $this->hitungTotalBiaya($pengajuan),
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                    'status_pembayaran' => 'diproses',
                ]);
            }

            return redirect()->route('pegawai.pengajuan.index')
                ->with('message', 'Pengajuan Telah ' . ucfirst($request->status_pengajuan) . '!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses pengajuan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(FormPengajuan $pengajuan)
    {
        if ($pengajuan->status === 'diproses') {
            return Redirect::back()->withErrors('status', 'Status Pengajuan Belum Berhasil Di Terima atau Di Tolak');
        }

        $pengajuan->delete();

        return Redirect::back()->with('message', 'Pengajuan Berhasil Dihapus');
    }
}
