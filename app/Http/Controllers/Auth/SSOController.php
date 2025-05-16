<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class SSOController extends Controller
{
    private $clientId = 'b41e9d5b-938b-447d-80cf-d4f53d253acf';
    private $clientSecret = 'r71UEJ4qoQ4lbaoOpD1SYF9hAvFwS1v4Ts1A65by';
    private $authorizeUrl = 'https://sakti.karanganyarkab.go.id/login/oauth/authorize';
    private $tokenUrl = 'https://sakti.karanganyarkab.go.id/login/oauth/access_token';
    private $apiUrl = 'https://sakti.karanganyarkab.go.id/api/user';

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
            return redirect('/')->withErrors(['SSO state mismatch']);
        }

        $response = Http::withoutVerifying()->asForm()->post(config('services.sso.token_url'), [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.sso.client_id'),
            'client_secret' => config('services.sso.client_secret'),
            'redirect_uri' => route('sso.callback'),
            'code' => $request->input('code'),
        ]);

        if ($response->failed()) {
            return redirect('/')->withErrors(['Failed to get access token']);
        }

        session(['access_token' => $response['access_token']]);

        return redirect('customer/dashboard');
    }

    public function logout()
    {
        session()->forget('access_token');
        return redirect('/');
    }

    public function user()
    {
        if (!session()->has('access_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $response = Http::withToken(session('access_token'))
            ->withHeaders([
                'Accept' => 'application/json',
                'User-Agent' => 'https://example-app.com/'
            ])
            ->get(config('services.sso.api_user_url'));

        return $response->json();
    }
}
