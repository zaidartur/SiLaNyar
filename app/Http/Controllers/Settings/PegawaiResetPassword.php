<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\PasswordOtp;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PegawaiResetPassword extends Controller
{
    public function lihatForm()
    {
        return Inertia::render('pegawai/LupaPassword');
    }

    public function kirimOtp(Request $request)
    {
        $request->validate([
            'identitas' => 'required',
            'via' => 'required|in:email,notelp',
        ]);

        $pegawai = Pegawai::where('email', $request->identitas)
            ->orWhere('no_telepon', $request->identitas)
            ->first();

        $otp = rand(100000, 999999);

        PasswordOtp::create([
            'identitas' => $request->identitas,
            'via' => $request->via,
            'otp' => $otp,
            'expired' => now()->addMinutes(5),
        ]);

        if ($request->via == 'email') {
            Mail::to($request->identitas)->send(new SendOtpMail($otp, $pegawai->nama));
        } else {
            $message = urlencode("*DLH Kabupaten Karanganyar*\n*$otp* adalah kode lupa password Anda. Demi keamanan, jangan bagikan kode ini kesiapapun.\n`Kode ini kedaluarsa dalam 5 menit`");
            $phone = $request->identitas;
            $url = "https://api.callmebot.com/whatsapp.php?phone=$phone&text=$message&apikey=2400230";

            try {
                file_get_contents($url);
            } catch (\Exception $err) {
                return Redirect::back()->withErrors(['whatsapp' => 'Gagal Mengirim Pesan Via Whatsapp.']);
            }
        }
        return Redirect::route('customer.password.reset')->with([
            'identitas' => $request->identitas,
            'via' => $request->via
        ]);
    }

    public function lihatOtpForm()
    {
        return Inertia::render('pegawai/VerifikasiOtp');
    }

    public function verifikasiOtp(Request $request)
    {
        $request->validate([
            'identitas' => 'required',
            'otp' => 'required'
        ]);

        $otpRecord = PasswordOtp::where('identitas', $request->identitas)
                                ->where('otp', $request->otp)
                                ->where('expired_at', '>', now())
                                ->latest()
                                ->first();

        if(!$otpRecord)
        {
            return Redirect::back()->withErrors(['otp' => 'OTP Yang Anda Masukkan Salah atau Sudah Kedaluarsa.']);
        }

        Inertia::render('customer/GantiPassword', [
            'identitas' => $request->identitas
        ]);
    }

    public function gantiPassword(Request $request)
    {
        $request->validate([
            'identitas' => 'required',
            'password' => 'required|confirmed'
        ]);

        $pegawai = Pegawai::where('email', $request->identitas)
                          ->orWhere('no_telepon', $request->identitas)
                          ->first();

        if(!$pegawai)
        {
            return Redirect::back()->withErrors(['identitas' => 'User Tidak Ditemukan']);
        }

        $pegawai->password = Hash::make($request->password);
        $pegawai->save();

        return Redirect::route('pegawai.login')->with('message', 'Password Berhasil Diubah!');
    }
}
