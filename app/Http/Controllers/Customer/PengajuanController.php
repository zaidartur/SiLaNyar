<?php

namespace App\Http\Controllers\Customer;

use App\Models\FormPengajuan;
use App\Models\Instansi;
use App\Models\Jadwal;
use App\Models\JenisCairan;
use App\Models\Kategori;
use App\Models\ParameterUji;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PengajuanController extends Controller
{
    private function hitungTotalBiaya(FormPengajuan $pengajuan)
    {
        $kategori = $pengajuan->kategori;
        $parameterDipilih = $pengajuan->parameter;

        $subkategori = $kategori->subkategori;

        if ($subkategori->isNotEmpty()) {
            $parameterSubkategori = $subkategori->flatMap(fn($sub) => $sub->parameter);

            $isFullSubkategoriPaket = $parameterDipilih->count() === $parameterSubkategori->count() &&
                $parameterDipilih->pluck('id')->sort()->values()->toArray() ===
                $parameterSubkategori->pluck('id')->sort()->values()->toArray();

            if ($isFullSubkategoriPaket) {
                return $kategori->harga;
            } else {
                return $parameterDipilih->sum('harga');
            }
        } else {
            $parameterKategori = $kategori->parameter;

            $isFullKategoriPaket = $parameterDipilih->count() === $parameterKategori->count() &&
                $parameterDipilih->pluck('id')->sort()->values()->toArray() ===
                $parameterKategori->pluck('id')->sort()->values()->toArray();

            if ($isFullKategoriPaket) {
                return $kategori->harga;
            } else {
                return $parameterDipilih->sum('harga');
            }
        }
    }

    //list pengajuan dari customer
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $instansi = Instansi::where('id_user', $user->id)
            ->get();

        if ($instansi->isEmpty()) {
            return Redirect::back()->with('error', 'Anda Belum Memiliki Instansi. Silahkan Tambahkan Instansi Terlebih Dahulu');
        }

        $jenis_cairan = JenisCairan::all();
        $kategori = Kategori::with('parameter', 'subkategori.parameter')->get();
        $parameter = ParameterUji::all();

        return Inertia::render('customer/pengajuan/Index', [
            'kategori' => $kategori,
            'jenis_cairan' => $jenis_cairan,
            'parameter' => $parameter,
            'instansi' => $instansi
        ]);
    }

    //proses daftar pengajuan uji lab customer
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $jenisCairan = JenisCairan::findOrFail($request->id_jenis_cairan);

        $rules = [
            'id_instansi' => 'required|exists:instansi,id',
            'id_jenis_cairan' => 'required|exists:jenis_cairan,id',
            'volume_sampel' => ['required', 'numeric', "min:{$jenisCairan->batas_minimum}"],
            'metode_pengambilan' => 'required|in:diantar,diambil',
            'lokasi' => 'required_if:metode_pengambilan,diambil|string',
            'waktu_pengambilan' => 'nullable|date|after_or_equal:today',
            'id_kategori' => 'required|exists:kategori,id',
            'parameter' => 'required|array',
            'parameter.*' => 'exists:parameter_uji,id',
            'keterangan' => 'nullable|string|max:255',
        ];

        if (!is_null($jenisCairan->batas_maksimum)) {
            $rules['volume_sampel'][] = "max:{$jenisCairan->batas_maksimum}";
        }

        $validated = $request->validate($rules, [
            'id_instansi.required' => 'Instansi Harus Diisi.',
            'id_instansi.exists' => 'Instansi Data Tidak Valid.',
            'id_jenis_cairan.required' => 'Jenis Cairan Harus Diisi.',
            'id_jenis_cairan.exists' => 'Jenis Cairan Data Tidak Valid.',
            'metode_pengambilan.required' => 'Metode Pengambilan Harus Diisi.',
            'metode_pengambilan.in' => 'Status Tidak Valid.',
            'lokasi.required_if' => 'Lokasi Wajib Diisi Jika Metode Pengambilan Diambil.',
            'waktu_pengambilan.date' => 'Waktu Pengambilan Harus Bertipe Tanggal.',
            'waktu_pengambilan.after_or_equal' => 'Waktu Pengambilan Tidak Boleh Sebelum Hari Ini.',
            'id_kategori.required' => 'Kategori Wajib Diisi.',
            'id_kategori.exists' => 'Kategori Data Tidak Valid.',
            'parameter.required' => 'Parameter Wajib Diisi.',
            'parameter.array' => 'Format Parameter Tidak Valid.',
            'parameter.*.required' => 'Parameter Wajib Diisi.',
            'parameter.*.exists' => 'Parameter Data Tidak Valid.',
            'keterangan.max' => 'Keterangan Maksimal 255 Kata',
            'volume_sampel.min' => "Volume Sampel Harus Diantara {$jenisCairan->batas_minimum} atau {$jenisCairan->batas_maksimum} Untuk Jenis Cairan",
            'volume_sampel.max' => "Volume Sampel Harus Diantara {$jenisCairan->batas_minimum} atau {$jenisCairan->batas_maksimum} Untuk Jenis Cairan"
        ]);

        if ($validated['metode_pengambilan'] === 'diantar') {
            $rules['waktu_pengambilan'] = 'required|date|after_or_equal:today';
            $rules['lokasi'] = 'Jl. Lawu No.204, Tegalasri, Bejen, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah 57716 (DLH Kabupaten Karanganyar)';
        }

        $pengajuanAktif = FormPengajuan::where('id_instansi', $validated['id_instansi'] ?? null)
            ->whereHas('instansi', function ($query) use ($user) {
                $query->whereIn('id', $user->instansi()->pluck('id')->toArray());
            })
            ->whereNotIn('status_pengajuan', ['diterima', 'ditolak'])
            ->first();

        if ($pengajuanAktif) {
            return redirect()->back()->withErrors([
                'Status' => 'Anda Tidak Diperbolehkan Menambah Pengajuan Jika Pengajuan Di Instansi Sebelumnya Belum Selesai'
            ]);
        }

        $pengajuan = FormPengajuan::create([
            'id_instansi' => $validated['id_instansi'] ?? null,
            'id_kategori' => $validated['id_kategori'] ?? null,
            'id_jenis_cairan' => $validated['id_jenis_cairan'],
            'volume_sampel' => $validated['volume_sampel'],
            'metode_pengambilan' => $validated['metode_pengambilan'],
            'lokasi' => $validated['lokasi'],
        ]);

        if (!empty($validated['parameter'])) {
            $pengajuan->parameter()->attach($validated['parameter']);
        }

        if (!empty($validated['parameter'] && !empty($validated['id_kategori']))) {
            $idOrder = $pengajuan->pembayaran->id_order ?? 'ORD-' . strtoupper(Str::random(10));

            Pembayaran::create([
                'id_order' => $idOrder,
                'id_form_pengajuan' => $pengajuan->id,
                'total_biaya' => $this->hitungTotalBiaya($pengajuan),
                'status_pembayaran' => 'belum_dibayar',
            ]);
        }

        if ($validated['metode_pengambilan'] === 'diantar') {
            Jadwal::create([
                'id_form_pengajuan' => $pengajuan->id,
                'id_user' => $user->id,
                'waktu_pengambilan' => $validated['waktu_pengambilan'],
                'keterangan' => $validated['keterangan'] ?? null,
                'status' => 'diproses'
            ]);
        }

        if (!$pengajuan) {
            return redirect()->back()->withErrors(['error' => 'Gagal membuat pengajuan']);
        }

        return redirect()->route('customer.dashboard')
            ->with('message', 'Pengajuan Berhasil Ditambahkan');
    }

    //lihat detail pengajuan dari user
    public function show($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $idInstansi = $user->instansi()->pluck('id')->toArray();

        $pengajuan = FormPengajuan::with(['kategori', 'parameter', 'jenis_cairan', 'instansi.user', 'pembayaran'])
            ->where('id', $id)
            ->whereIn('id_instansi', $idInstansi)
            ->firstOrFail();

        return Inertia::render('customer/pengajuan/Detail', [
            'pengajuan' => $pengajuan
        ]);
    }

    public function edit(FormPengajuan $pengajuan)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $idInstansi = $user->instansi()->pluck('id')->toArray();

        $pengajuan->load(['kategori', 'parameter', 'instansi.user', 'jenis_cairan']);

        if (!in_array($pengajuan->id_instansi, $idInstansi)) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        if ($pengajuan->status_pengajuan !== 'proses_validasi') {
            abort(403);
            return Redirect::back()->withErrors('Proses Status Anda Sudah Tidak Diproses Harap Mengajukan Kembali!');
        }

        $jenis_cairan = JenisCairan::all();
        $kategori = Kategori::with('parameter', 'subkategori.parameter')->get();
        $parameter = ParameterUji::all();

        return Inertia::render('customer/pengajuan/Edit', [
            'pengajuan' => $pengajuan,
            'kategori' => $kategori,
            'jenis_cairan' => $jenis_cairan,
            'parameter' => $parameter
        ]);
    }

//proses update pengajuan uji lab customer
    public function update(Request $request, FormPengajuan $pengajuan)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $idInstansi = $user->instansi()->pluck('id')->toArray();

        $pengajuanAktif = FormPengajuan::where('id_instansi', $idInstansi)
            ->whereIn('status_pengajuan', ['diterima', 'ditolak'])
            ->where('id', '!=', $pengajuan->id)
            ->first();

        if ($pengajuanAktif) {
            return Redirect::back()->withErrors(['Status' => 'Anda Tidak Diperbolehkan Mengubah Pengajuan Jika Pengajuan Sudah Di Verifikasi']);
        }

        $jenisCairan = JenisCairan::findOrFail($request->id_jenis_cairan);

        $rules = [
            'id_instansi' => 'required|exists:instansi,id',
            'id_jenis_cairan' => 'required|exists:jenis_cairan,id',
            'volume_sampel' => ['required', 'numeric', "min:{$jenisCairan->batas_minimum}"],
            'metode_pengambilan' => 'required|in:diantar,diambil',
            'lokasi' => 'required_if:metode_pengambilan,diambil|string',
            'waktu_pengambilan' => 'nullable|date|after_or_equal:today',
            'id_kategori' => 'required|exists:kategori,id',
            'parameter' => 'required|array',
            'parameter.*' => 'exists:parameter_uji,id',
            'keterangan' => 'nullable|string|max:255',
        ];

        if (!is_null($jenisCairan->batas_maksimum)) {
            $rules['volume_sampel'][] = "max:{$jenisCairan->batas_maksimum}";
        }

        $validated = $request->validate($rules, [
            'id_instansi.required' => 'Instansi Harus Diisi.',
            'id_instansi.exists' => 'Instansi Data Tidak Valid.',
            'id_jenis_cairan.required' => 'Jenis Cairan Harus Diisi.',
            'id_jenis_cairan.exists' => 'Jenis Cairan Data Tidak Valid.',
            'metode_pengambilan.required' => 'Metode Pengambilan Harus Diisi.',
            'metode_pengambilan.in' => 'Status Tidak Valid.',
            'lokasi.required_if' => 'Lokasi Wajib Diisi Jika Metode Pengambilan Diambil.',
            'waktu_pengambilan.date' => 'Waktu Pengambilan Harus Bertipe Tanggal.',
            'waktu_pengambilan.after_or_equal' => 'Waktu Pengambilan Tidak Boleh Sebelum Hari Ini.',
            'id_kategori.required' => 'Kategori Wajib Diisi.',
            'id_kategori.exists' => 'Kategori Data Tidak Valid.',
            'parameter.required' => 'Parameter Wajib Diisi.',
            'parameter.array' => 'Format Parameter Tidak Valid.',
            'parameter.*.required' => 'Parameter Wajib Diisi.',
            'parameter.*.exists' => 'Parameter Data Tidak Valid.',
            'keterangan.max' => 'Keterangan Maksimal 255 Kata',
            'volume_sampel.min' => "Volume Sampel Harus Diantara {$jenisCairan->batas_minimum} atau {$jenisCairan->batas_maksimum} Untuk Jenis Cairan",
            'volume_sampel.max' => "Volume Sampel Harus Diantara {$jenisCairan->batas_minimum} atau {$jenisCairan->batas_maksimum} Untuk Jenis Cairan",
        ]);

        if ($validated['metode_pengambilan'] === 'diantar') {
            $rules['waktu_pengambilan'] = 'required|date|after_or_equal:today';
            $validated['lokasi'] = 'Jl. Lawu No.204, Tegalasri, Bejen, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah 57716 (DLH Kabupaten Karanganyar)';
        }

        // Update data pengajuan
        $pengajuan->update([
            'id_instansi' => $validated['id_instansi'] ?? null,
            'id_kategori' => $validated['id_kategori'] ?? null,
            'id_jenis_cairan' => $validated['id_jenis_cairan'],
            'volume_sampel' => $validated['volume_sampel'],
            'metode_pengambilan' => $validated['metode_pengambilan'],
            'lokasi' => $validated['lokasi'],
        ]);

        if (!empty($validated['parameter'] && !empty($validated['id_kategori']))) {
            $pembayaran = $pengajuan->pembayaran;
            if ($pembayaran) {
                $pembayaran->update([
                    'total_biaya' => $this->hitungTotalBiaya($pengajuan),
                    'status_pembayaran' => 'belum_dibayar',
                ]);
            }
        }

        if (!empty($validated['parameter'])) {
            $pengajuan->parameter()->sync($validated['parameter']);
        } else {
            $pengajuan->parameter()->detach();
        }

        if ($validated['metode_pengambilan'] === 'diantar') {
            $jadwal = $pengajuan->jadwal;
            if ($jadwal) {
                $jadwal->update([
                    'id_user' => $user->id,
                    'waktu_pengambilan' => $validated['waktu_pengambilan'],
                    'keterangan' => $validated['keterangan'] ?? null,
                    'status' => 'diproses'
                ]);
            } else {
                Jadwal::create([
                    'id_form_pengajuan' => $pengajuan->id,
                    'id_user' => $user->id,
                    'waktu_pengambilan' => $validated['waktu_pengambilan'],
                    'keterangan' => $validated['keterangan'] ?? null,
                    'status' => 'diproses'
                ]);
            }
        } else {
            $pengajuan->jadwal()->delete();
        }

        return redirect()->route('customer.dashboard')
            ->with('message', 'Pengajuan Berhasil Diupdate');
    }

    public function destroy($id)
    {
        $pengajuan = FormPengajuan::where('status_pengajuan', ['proses_validasi', 'ditolak'])->findOrFail($id);

        if ($pengajuan->status_pengajuan === 'diterima') {
            return Redirect::back()->with('error', 'Hapus Pengajuan Anda Ditolak Karena Telah Melewati Proses Verifikasi');
        }

        $pengajuan->delete();

        return Redirect::route('customer.dashboard');
    }
}
