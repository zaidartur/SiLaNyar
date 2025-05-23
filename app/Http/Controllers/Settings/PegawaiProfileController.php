<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Instansi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

class PegawaiProfileController extends Controller
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

        $userRole = User::where('id', $user->id)->get();

        return Inertia::render('pegawai/profile/Index', [
            'user' => $userData,
            'userRole' => $userRole
        ]);
    }
}
