<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FormPengajuan;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class JadwalPengantaranController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        $jadwal = Jadwal::where('metode_pengambilan', 'diantar')->get();

        return Inertia::render('customer/pengantaran/Index', [
            'jadwal' => $jadwal,
            'customer' => $customer
        ]);
    }

    public function edit(Jadwal $jadwal)
    {
        $customer = Auth::guard('customer')->user();

        $form_pengajuan = FormPengajuan::where('id_customer', $customer->id)
            ->where('metode_pengambilan', 'diantar')
            ->get();

        return Inertia::render('customer/pengantaran/Edit', [
            'form_pengajuan' => $form_pengajuan,
            'jadwal' => $jadwal
        ]);
    }

    public function update(Jadwal $jadwal, Request $request)
    {
        if ($jadwal->status === 'selesai') {
            return Redirect::back()->withErrors([
                'status' => 'Jadwal Yang Sudah Selesai Tidak Dapat Diubah!'
            ]);
        }

        $batasEditTanggal = $jadwal->waktu_pengambilan->copy()->subDay();

        if (now()->greaterThan($batasEditTanggal)) {
            return Redirect::back()->withErrors([
                'waktu_pengambilan' => 'Jadwal Tidak Dapat Diganti Karena Sudah Melewati Batas Reschedule!'
            ]);
        }

        $request->validate([
            'id_form_pengajuan' => 'required',
            'waktu_pengambilan' => 'required|date|after_or_equal:today',
            'status' => 'required|in:diproses,selesai',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $jadwal->update($request->all());

        return Redirect::route('customer.pengantaran.index')->with('message', 'Jadwal Pengantaran Berhasil Terupdate!');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        if ($jadwal->status !== 'selesai') {
            return Redirect::back()->withErrors([
                'status' => 'Jadwal Tidak Dapat Dihapus Karena Jadwal Belum Selesai!'
            ]);
        }

        $jadwal->delete();

        return Redirect::route('customer.pengantaran.index')->with('message', 'Jadwal Pengantaran Berhasil Dihapus!');
    }
}
