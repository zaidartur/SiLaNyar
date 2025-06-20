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
    public function index(Request $request)
    {
        $searchByStatus = $request->input('status');

        $pengajuan = FormPengajuan::with(['kategori', 'parameter', 'instansi.user', 'jenis_cairan'])
            ->when($searchByStatus, function ($query, $status) {
                $query->where('status_pengajuan', $status);
            })
            ->orderByDesc('updated_at')
            ->get();

        return Inertia::render('pegawai/pengajuan/Index', [
            'pengajuan' => $pengajuan,
            'filter' => [
                'status' => $searchByStatus
            ]
        ]);
    }

    //lihat detail pengajuan dari pegawai
    public function show($id)
    {
        $pengajuan = FormPengajuan::with(['kategori.parameter', 'kategori.subkategori.parameter', 'parameter', 'instansi.user', 'jenis_cairan'])
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
            }

            $validated = $request->validate($rules);

            $pengajuan->status_pengajuan = $validated['status_pengajuan'];

            if ($pengajuan->metode_pengambilan === 'diantar') {
                $kategori = Kategori::with('parameter', 'subkategori.parameter')->find($validated['id_kategori']);
                
                $allowedParameterIds = collect();
                if ($kategori) {
                    $allowedParameterIds = $allowedParameterIds->merge($kategori->parameter->pluck('id'));
                    
                    foreach ($kategori->subkategori as $subkategori) {
                        $allowedParameterIds = $allowedParameterIds->merge($subkategori->parameter->pluck('id'));
                    }
                }
                
                $allowedParameterIds = $allowedParameterIds->unique();
                
                $invalidParameters = collect($validated['parameter'])->diff($allowedParameterIds);
                
                if ($invalidParameters->isNotEmpty()) {
                    return redirect()->back()
                        ->with('error', 'Beberapa parameter yang dipilih tidak sesuai dengan kategori yang dipilih.')
                        ->withInput();
                }

                $pengajuan->id_kategori = $validated['id_kategori'];
                $pengajuan->parameter()->sync($validated['parameter']);

                Pembayaran::updateOrCreate(
                    ['id_form_pengajuan' => $pengajuan->id],
                    [
                        'id_order' => 'INV-' . strtoupper(Str::random(10)) . '-' . time(),
                        'total_biaya' => $this->hitungTotalBiaya($pengajuan),
                        'metode_pembayaran' => 'transfer',
                        'status_pembayaran' => 'belum_dibayar',
                    ]
                );
            }

            $pengajuan->save();

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
