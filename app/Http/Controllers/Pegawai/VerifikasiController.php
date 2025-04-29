<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Customer;
use App\Models\pegawai;

class VerifikasiController extends Controller
{

    //proses verifikasi customer
    public function verifikasiCustomer(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'status_verifikasi' => 'required|in:diterima,ditolak,diproses'
        ]);

        $customer->update([
            'status_verifikasi' => $request->status_verifikasi
        ]);

        return Redirect::back()->with('message', 'Status Berhasil Diperbarui!');
    }

    //proses verifikasi pegawai
    public function verifikasiPegawai(Request $request, $id)
    {
        $pegawai = pegawai::findOrFail($id);

        $request->validate([
            'status_verifikasi' => 'required|in:diterima,ditolak'
        ]);

        $pegawai->update([
            'status_verifikasi' => $request->status_verifikasi
        ]);

        return Redirect::back()->with('message', 'Status Berhasil Diperbarui!');
    }
}
