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
            ->whereUuidOrId($id)
            ->firstOrFail();

        return Inertia::render('pegawai/pengajuan/Detail', [
            'pengajuan' => $pengajuan
        ]);
    }

    //edit pengajuan dari pegawai
    public function edit(FormPengajuan $pengajuan)
    {
        $pengajuan->load(['kategori', 'parameter', 'instansi.user', 'jenis_cairan']);

        $kategoriList = Kategori::with('parameter', 'subkategori.parameter')->select('id', 'uuid', 'nama')->get();
        $parameterList = ParameterUji::select('id', 'uuid', 'nama_parameter')->get();

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
            $pengajuan = FormPengajuan::with(['kategori', 'parameter', 'instansi.user', 'jadwal'])->whereUuidOrId($id)->firstOrFail();

            $rules = [
                'status_pengajuan' => 'required|in:diterima,ditolak'
            ];

            if ($pengajuan->metode_pengambilan === 'diantar' && $request->input('status_pengajuan') === 'diterima') {
                $rules['id_kategori'] = ['required', function ($attribute, $value, $fail) {
                    if (!Kategori::whereUuidOrId($value)->exists()) {
                        $fail('Kategori Data Tidak Valid.');
                    }
                }];
                $rules['parameter'] = 'required|array';
                $rules['parameter.*'] = [function ($attribute, $value, $fail) {
                    if (!ParameterUji::whereUuidOrId($value)->exists()) {
                        $fail('Parameter Data Tidak Valid.');
                    }
                }];
            }

            $validated = $request->validate($rules);

            $pengajuan->status_pengajuan = $validated['status_pengajuan'];

            if ($pengajuan->metode_pengambilan === 'diantar' && $validated['status_pengajuan'] === 'diterima') {
                $kategori = Kategori::with('parameter', 'subkategori.parameter')->whereUuidOrId($validated['id_kategori'])->first();

                $allowedParameterIdentifiers = collect();
                if ($kategori) {
                    $allowedParameterIdentifiers = $allowedParameterIdentifiers->merge($kategori->parameter->pluck('uuid'))->merge($kategori->parameter->pluck('id'));

                    foreach ($kategori->subkategori as $subkategori) {
                        $allowedParameterIdentifiers = $allowedParameterIdentifiers->merge($subkategori->parameter->pluck('uuid'))->merge($subkategori->parameter->pluck('id'));
                    }
                }

                $allowedParameterIdentifiers = $allowedParameterIdentifiers->unique();

                $invalidParameters = collect($validated['parameter'])->diff($allowedParameterIdentifiers);

                if ($invalidParameters->isNotEmpty()) {
                    return redirect()->back()
                        ->with('error', 'Beberapa parameter yang dipilih tidak sesuai dengan kategori yang dipilih.')
                        ->withInput();
                }

                $pengajuan->id_kategori = $kategori?->uuid;
                $pengajuan->save();

                $paramUuids = ParameterUji::where(function ($q) use ($validated) {
                    $q->whereIn('uuid', $validated['parameter'])
                      ->orWhereIn('id', $validated['parameter']);
                })->pluck('uuid')->toArray();

                $pengajuan->parameter()->sync($paramUuids);
                $pengajuan->refresh();

                Pembayaran::updateOrCreate(
                    ['id_form_pengajuan' => $pengajuan->uuid],
                    [
                        'id_order' => 'INV-' . strtoupper(Str::random(10)) . '-' . time(),
                        'total_biaya' => $this->hitungTotalBiaya($pengajuan),
                        'metode_pembayaran' => null,
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
