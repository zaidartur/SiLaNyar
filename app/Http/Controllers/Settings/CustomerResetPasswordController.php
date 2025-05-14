<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PasswordOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Mail\SendOtpMail;
use Illuminate\Container\Attributes\Auth;

class CustomerResetPasswordController extends Controller
{
    public function lihatForm()
    {
        return Inertia::render('customer/LupaPassword');    
    }

    public function kirimOtp(Request $request)
    {
        $request->validate([
            'identitas' => 'required',
            'via' => 'required|in:email,whatsapp'
        ]);

        $customer = Customer::where('email', $request->identitas)
                            ->orWhere('kontak_pribadi', $request->identitas)
                            ->orWhere('kontak_instansi', $request->identitas)
                            ->firstOrFail();

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordOtp::create([
            'identitas' => $request->identitas,
            'via' => $request->via,
            'otp' => $otp,
            'expired_at' => now()->addMinutes(5),
        ]);

        if ($request->via === 'email') {
            Mail::to($request->identitas)
                ->send(new SendOtpMail($otp, $customer->nama));
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

        return redirect()->route('customer.password.reset')
            ->with([
                'identitas' => $request->identitas,
                'via' => $request->via
            ]);
    }

    public function lihatOtpForm()
    {
        return Inertia::render('customer/VerifikasiOTP');    
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

        return Inertia::render('customer/GantiPassword', [
            'identitas' => $request->identitas
        ]);
    }

    public function gantiPassword(Request $request)
    {
        $request->validate([
            'identitas' => 'required',
            'password' => 'required|confirmed',
        ]);

        $customer = Customer::where('email', $request->identitas)
                    ->orWhere('kontak_pribadi', $request->identitas)
                    ->orWhere('kontak_instansi', $request->identitas)
                    ->first();

        if(!$customer)
        {
            return Redirect::back()->withErrors(['identitas' => 'User Tidak Ditemukan']);
        }

        $customer->password = Hash::make($request->password);
        $customer->save();

        return Redirect::route('customer.login')->with('message', 'Password Berhasil Diubah!');
    }
}
