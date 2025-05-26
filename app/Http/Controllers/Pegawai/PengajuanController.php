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

class PengajuanController extends Controller
{
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
        $pengajuan = FormPengajuan::with(['kategori', 'parameter', 'instansi.user'])->findOrFail($id);

        $rules = [
            'status_pengajuan' => 'required|in:diterima,ditolak'
        ];

        if ($pengajuan->metode_pengambilan === 'diantar') {
            $rules['id_kategori'] = 'required|exists:kategori,id';
            $rules['parameter'] = 'required|array';
            $rules['parameter.*'] = 'exists:parameter_uji,id';
        }

        $validated = $request->validate($rules);

        $pengajuan->status_pengajuan = $validated['status_pengajuan'];

        if ($pengajuan->metode_pengajuan === 'diantar') {
            $pengajuan->id_kategori = $validated['id_kategori'];

            $pengajuan->parameter()->sync($validated['parameter']);
        }

        $pengajuan->save();

        return redirect()->route('pegawai.pengajuan.index')
            ->with('message', 'Pengajuan Telah ' . ucfirst($request->status_pengajuan) . '!');
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
