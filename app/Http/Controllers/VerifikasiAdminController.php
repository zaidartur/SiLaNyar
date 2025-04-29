<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class VerifikasiAdminController extends Controller
{
    //lihat detail customer
    public function showCustomer(Customer $customer)
    {
        return Inertia::render('customer/show', [
            'customer' => $customer
        ]);
    }

    //proses verifikasi customer
    public function verifikasiCustomer (Request $request, $id)
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

    //lihat detail pegawai
    public function showPegawai(pegawai $pegawai)
    {
        $pegawai->load('roles');
        
        return Inertia::render('pegawai/show', [
            'pegawai' => $pegawai
        ]);
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
