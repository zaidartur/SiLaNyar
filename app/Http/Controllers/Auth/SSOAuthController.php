<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SSOAuthController extends Controller
{
    public function redirect()
    {
        $state = Str::random(40);
        session(['sso_state' => $state]);
        
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => config('services.sso.client_id'),
            'redirect_uri' => config('services.sso.redirect'),
            'state' => $state,
            'scope' => 'user',
        ]);

        return redirect(config('services.sso.auth_url').'?'.$query);
    }

    public function callback()
    {
        $state = request('state');
        $code = request('code');
        
        if(!$state || $state !== session('sso_state')) {
            abort(403, 'Invalid State');
        }

        $response = Http::asForm()->post(config('services.sso.token_url'), [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.sso.client_id'),
            'client_secret' => config('services.sso.client_secret'),
            'redirect_uri' => config('services.sso.redirect'),
            'code' => $code
        ]);

        if(!$response->ok() || !isset($response->json()['access_token'])) {
            abort(500, 'Gagal Mendapatkan Token');
        }

        $accessToken = $response->json()['access_token'];

        $userResponse = Http::withToken($accessToken)
                            ->acceptJson()
                            ->get(config('services.sso.user_url'));

        if(!$userResponse->ok()) {
            abort(500, 'Gagal Mendapatkan User Info');
        }

        $userInfo = $userResponse->json();

        if(!isset($userInfo['email']) || !isset($userInfo['name'])) {
            abort(500, 'Data Pengguna Tidak Lengkap');
        }

        $customer = Customer::updateOrCreate(
            ['email' => $userInfo['email']],
            ['nama' => $userInfo['name']],
        );

        Auth::guard('customer')->login($customer);

        return redirect()->intended('/dashboard');
    }

    public function logout()
    {
        Auth::guard('customer')->logout();

        Session::forget('access_token');
        Session::forget('customer');

        return Redirect::route('/');
    }
}
