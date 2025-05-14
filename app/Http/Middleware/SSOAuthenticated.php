<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SSOAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    private function fetchSsoUser($accessToken)
    {
        $url =   'https://sakti.karanganyarkab.go.id/api/user';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.$accessToken,
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('access_token')) {
            return Redirect::route('customer.sso.login');
        }

        $userData = $this->fetchSsoUser(Session::get('access_token'));

        if (!$userData || !isset($userData['email'])) {
            Session::forget('access_token');
            return Redirect::route('customer.sso.login')->withErrors(['message' => 'Gagal Mendapatkan Data SSO']);
        }

        $customer = Customer::updateOrCreate(
            ['email' => $userData['email']],
            [
                'nama' => $userData['name'] ?? '',
                'nik' => $userData['nik'] ?? '',
                'jabatan' => $userData['jabatan'] ?? '',
                'no_telepon' => $userData['no_telepon'] ?? '',
            ]
        );

        Auth::guard('customer')->login($customer);
        
        return $next($request);
    }
}
