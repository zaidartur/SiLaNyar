<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Instansi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

class CustomerProfileController extends Controller
{
    private function user()
    {
        if (!session()->has('access_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $response = Http::withoutVerifying()->withToken(session('access_token'))
            ->withHeaders([
                'Accept' => 'application/json',
                'User-Agent' => 'https://example-app.com/'
            ])
            ->get(config('services.sso.api_user_url'));

        return $response->json();
    }

    public function show()
    {
        $userData = $this->user();

        if (isset($userData['error'])) {
            return redirect()->route('login')->with('error', 'Unauthorized');
        }

        $user = Auth::user();
        $instansiData = Instansi::where('id_user', $user->id)->get();

        return Inertia::render('customer/profile/Index', [
            'user' => $userData,
            'instansi' => $instansiData
        ]);
    }

    public function showInstansi(Instansi $instansi)
    {
        $user = Auth::user();

        if(!$instansi->id_user !== $user->id)
        {
            abort(403, 'Akses Tidak Diizinkan');
        }

        $instansi->surat_keterangan_penugasan_url = $instansi->surat_keterangan_penugasan
                    ? asset('storage/'.$instansi->surat_keterangan_penugasan)
                    : null;

        $instansi->foto_kartu_identitas_url = $instansi->foto_kartu_identitas
                    ? asset('storage/'.$instansi->foto_kartu_identitas)
                    : null;

        return Inertia::render('customer/profile/ShowInstansi', [
            'instansi' => $instansi
        ]);
    }

    public function storeInstansi(Request $request)
    {
        $user = Auth::user();

        $tanggal = now()->format('dmY');
        $namaUser = str_replace('', '_', strtolower($user->nama));
        $randomId = Str::random(6);

        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:swasta,pemerintahan,pribadi',
            'alamat' => 'required|string|max:255',
            'wilayah' => 'required|string|max:255',
            'desa_kelurahan' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:instansi,email',
            'no_telepon' => ['required', 'string', 'regex:/^(08|\+62|62)[0-9]{7,13}$/'],
            'posisi_jabatan' => 'required|string|max:255',
            'departemen_divisi' => 'required|string|max:255',
            'surat_keterangan_penugasan' => 'required|file|mimes:pdf|max:2048',
            'foto_kartu_identitas' => 'required|file|mimes:pdf,jpeg,jpg,png|max:2048',
        ]);

        if (!$request->hasFile('surat_keterangan_penugasan') || !$request->file('surat_keterangan_penugasan')->isValid()) {
            return Redirect::back()->withErrors(['surat_keterangan_penugasan' => 'File surat tidak valid. Harap menggunakan file berformat PDF']);
        }

        if (!$request->hasFile('foto_kartu_identitas') || !$request->file('foto_kartu_identitas')->isValid()) {
            return Redirect::back()->withErrors(['foto_kartu_identitas' => 'File foto identitas tidak valid. Harap menggunakan file berformat PDF, jpeg, jpg, png']);
        }

        $surat = $request->file('surat_keterangan_penugasan');
        $foto = $request->file('foto_kartu_identitas');

        $suratFileName = 'surat_keterangan_' . $namaUser . '_' . $tanggal . '_' . $randomId . '.' . $surat->getClientOriginalExtension();
        $fotoFileName = 'foto_kartu_identitas_' . $namaUser . '_' . $tanggal . '_' . $randomId . '.'  . $foto->getClientOriginalExtension();

        $suratPath = $surat->storeAs('surat_keterangan', $suratFileName, 'public');
        $fotoPath = $foto->storeAs('foto_kartu_identitas', $fotoFileName, 'public');

        if (!$suratPath || !$fotoPath) {
            return Redirect::back()->withErrors(['file' => 'Gagal menyimpan file.']);
        }

        $instansi = Instansi::create([
            'id_user' => $user->id,
            'nama' => $request->nama,
            'tipe' => $request->tipe,
            'alamat' => $request->alamat,
            'wilayah' => $request->wilayah,
            'desa_kelurahan' => $request->desa_kelurahan,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'posisi_jabatan' => $request->posisi_jabatan,
            'departemen_divisi' => $request->departemen_divisi,
            'surat_keterangan_penugasan' => $suratPath,
            'foto_kartu_identitas' => $fotoPath
        ]);

        if (!$instansi) {
            return Redirect::back()->withErrors(['form' => 'Data Instansi Gagal Dikirim!']);
        }

        return Redirect::route('customer.profile')->with('message', 'Instansi Berhasil Ditambah!');
    }

    public function editInstansi(Instansi $instansi)
    {
        $user = Auth::user();

        $instansi->where('id_user', $user)->get();

        return Inertia::render('customer/profile/EditInstansi', [
            'instansi' => $instansi
        ]);
    }

    public function updateInstansi(Instansi $instansi, Request $request)
    {
        $user = Auth::user();

        $tanggal = now()->format('dmY');
        $namaUser = str_replace('', '_', strtolower($user->nama));
        $randomId = Str::random(6);

        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:swasta,pemerintahan,pribadi',
            'alamat' => 'required|string|max:255',
            'wilayah' => 'required|string|max:255',
            'desa_kelurahan' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:instansi,email,' . $instansi->id,
            'no_telepon' => ['required', 'string', 'regex:/^(08|\+62|62)[0-9]{7,13}$/'],
            'posisi_jabatan' => 'required|string|max:255',
            'departemen_divisi' => 'required|string|max:255',
            'surat_keterangan_penugasan' => 'nullable|file|mimes:pdf|max:2048',
            'foto_kartu_identitas' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:2048',
        ]);

        if ($request->hasFile('surat_keterangan_penugasan')) {
            Storage::disk('public')->delete($instansi->surat_keterangan_penugasan);
            if (!$request->file('surat_keterangan_penugasan')->isValid()) {
                return Redirect::back()->withErrors(['surat_keterangan_penugasan' => 'File surat tidak valid']);
            }

            $surat = $request->file('surat_keterangan_penugasan');
            $suratFileName = 'surat_keterangan_' . $namaUser . '_' . $tanggal . '_' . $randomId . '.' . $surat->getClientOriginalExtension();
            $instansi->surat_keterangan_penugasan = $surat->storeAs('surat_keterangan', $suratFileName, 'public');
        }

        if ($request->hasFile('foto_kartu_identitas')) {
            if (!$request->file('foto_kartu_identitas')->isValid()) {
                return Redirect::back()->withErrors(['foto_kartu_identitas' => 'File foto tidak valid']);
            }

            $foto = $request->file('foto_kartu_identitas');
            $fotoFileName = 'foto_kartu_identitas_' . $namaUser . '_' . $tanggal . '_' . $randomId . '.'  . $foto->getClientOriginalExtension();
            $instansi->foto_kartu_identitas = $foto->storeAs('foto_kartu_identitas', $fotoFileName, 'public');
        }

        $instansi->update(
            [
                'id_user' => $user->id,
                'nama' => $request->nama,
                'tipe' => $request->tipe,
                'alamat' => $request->alamat,
                'wilayah' => $request->wilayah,
                'desa_kelurahan' => $request->desa_kelurahan,
                'email' => $request->email,
                'no_telepon' => $request->no_telepon,
                'posisi_jabatan' => $request->posisi_jabatan,
                'departemen_divisi' => $request->departemen_divisi,
                'surat_keterangan_penugasan' => $instansi->surat_keterangan_penugasan,
                'foto_kartu_identitas' => $instansi->foto_kartu_identitas,
            ]
        );

        return Redirect::route('customer.profile')->with('message', 'Data Instansi Berhasil Diupdate!');
    }
}
