<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class SSOController extends Controller
{
    public function redirect(Request $request)
    {
        $state = bin2hex(random_bytes(16));
        session(['oauth_state' => $state]);

        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => config('services.sso.client_id'),
            'redirect_uri' => route('sso.callback'),
            'scopes' => 'user',
            'state' => $state,
        ]);

        $url = config('services.sso.login_url') . '?client_id=' . config('services.sso.client_id') . '&return_to=' . urlencode('/login/oauth/authorize?' . $query);

        return redirect($url);
    }

    public function callback(Request $request)
    {
        if (!$request->has('code') || $request->input('state') !== session('oauth_state')) {
            Log::info('SSO state mismatch');
            return redirect('/')->withErrors(['SSO state mismatch']);
        }

        $response = Http::timeout(90)->withoutVerifying()->asForm()->post(config('services.sso.token_url'), [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.sso.client_id'),
            'client_secret' => config('services.sso.client_secret'),
            'redirect_uri' => route('sso.callback'),
            'code' => $request->input('code'),
        ]);

        if ($response->failed()) {
            Log::info('Failed to get access token');
            return redirect('/')->withErrors(['Failed to get access token']);
        }

        $aksesToken = $response['access_token'];
        session(['access_token' => $aksesToken]);

        $userResponse =  Http::withoutVerifying()->withToken($aksesToken)
            ->withHeaders([
                'Accept' => 'application/json',
                'User-agent' => 'https://silanyar.karanganyarkab.go.id/',
            ])
            ->get(config('services.sso.api_user_url'));

        if ($userResponse->failed()) {
            // return redirect('/')->withErrors(['Gagal Mengambil Informasi Data']);
            return Redirect::route('/')->withErrors(['Gagal Mengambil Informasi Data']);
            // iki sek ngubah aku jik, mbuh ngp nek nganggo sek "Redirect::route" test callback gagal ketika permintaan data user gagal e dadi gagal
        }

        $userData = $userResponse->json();

        $user = User::updateOrCreate(
            ['email' => $userData['email']],
            [
                'nama'           => $userData['nama'],
                'nik'            => $userData['nik'],
                'tanggal_lahir'  => $userData['tgl_lahir'],
                'rt'             => $userData['rt'],
                'rw'             => $userData['rw'],
                'kode_pos'       => $userData['kode_pos'],
                'alamat'         => $userData['alamat'],
                'username'       => $userData['username'] ?? $userData['email'],
                'no_telepon'     => $userData['no_wa'],
            ]
        );

        Auth::guard('web')->login($user);

        if ($user->roles->isEmpty()) {
            $user->assignRole('customer');
        }

        $role = $user->roles->first()?->name;

        switch ($role) {
            case 'superadmin':
            case 'admin':
            case 'teknisi':
                return Redirect::route('pegawai.dashboard');
            case 'customer':
                return Redirect::route('customer.dashboard');
            default:
                return Redirect::route('pegawai.dashboard');
        }
    }
    
    public function session_exists()
    {
        $user = User::where('id', Auth::user()->id)->first();
        if ($user->roles->isEmpty()) {
            $user->assignRole('customer');
        }

        $role = $user->roles->first()?->name;
        Log::debug('role', [$role]);

        switch ($role) {
            case 'superadmin':
            case 'admin':
            case 'teknisi':
                return Redirect::route('pegawai.dashboard');
            case 'customer':
                return Redirect::route('customer.dashboard');
            default:
                return Redirect::route('pegawai.dashboard');
        }
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        session()->forget('access_token');
        return redirect('/');
    }
}