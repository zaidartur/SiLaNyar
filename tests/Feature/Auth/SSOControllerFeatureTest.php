<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SSOControllerFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'customer', 'guard_name' => 'web', 'kode_role' => 'CUST']);
        Role::create(['name' => 'admin', 'guard_name' => 'web', 'kode_role' => 'ADM']);
        Role::create(['name' => 'superadmin', 'guard_name' => 'web', 'kode_role' => 'SADM']);
        Role::create(['name' => 'teknisi', 'guard_name' => 'web', 'kode_role' => 'TEKN']);

        // Set default SSO config for all tests
        config([
            'services.sso.client_id' => 'test_client_id',
            'services.sso.client_secret' => 'test_client_secret',
            'services.sso.login_url' => 'https://sso.example.com/login',
            'services.sso.token_url' => 'https://sso.example.com/token',
            'services.sso.api_user_url' => 'https://sso.example.com/user'
        ]);
    }

    public function test_redirect_menghasilkan_state_dan_mengarahkan_ke_sso()
    {
        $response = $this->get(route('sso.login'));

        $response->assertStatus(302);
        $this->assertNotNull(session('oauth_state'));
        $this->assertEquals(32, strlen(session('oauth_state')));
        
        // Build expected URL with proper encoding
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => 'test_client_id',
            'redirect_uri' => route('sso.callback'),
            'scopes' => 'user',
            'state' => session('oauth_state'),
        ]);
        
        $expectedUrl = 'https://sso.example.com/login?client_id=test_client_id&return_to=' . 
                      urlencode('/login/oauth/authorize?' . $query);
        
        $response->assertRedirect($expectedUrl);
    }

    public function test_callback_gagal_tanpa_code()
    {
        session(['oauth_state' => 'test_state']);

        $response = $this->get(route('sso.callback', ['state' => 'test_state']));

        $response->assertRedirect('/');
        $response->assertSessionHasErrors();
    }

    public function test_callback_gagal_dengan_state_tidak_valid()
    {
        session(['oauth_state' => 'valid_state']);

        $response = $this->get(route('sso.callback', [
            'code' => 'test_code',
            'state' => 'invalid_state'
        ]));

        $response->assertRedirect('/');
        $response->assertSessionHasErrors();
    }

    public function test_callback_gagal_ketika_permintaan_token_gagal()
    {
        session(['oauth_state' => 'test_state']);
        
        Http::fake([
            'https://sso.example.com/token' => Http::response([], 400)
        ]);

        $response = $this->get(route('sso.callback', [
            'code' => 'test_code',
            'state' => 'test_state'
        ]));

        $response->assertRedirect('/');
        $response->assertSessionHasErrors();
    }

    public function test_callback_gagal_ketika_permintaan_data_user_gagal()
    {
        session(['oauth_state' => 'test_state']);
        
        Http::fake([
            'https://sso.example.com/token' => Http::response([
                'access_token' => 'test_access_token'
            ], 200),
            'https://sso.example.com/user' => Http::response([], 400)
        ]);

        $response = $this->get(route('sso.callback', [
            'code' => 'test_code',
            'state' => 'test_state'
        ]));

        $response->assertRedirect('/');
        $response->assertSessionHasErrors();
    }

    public function test_callback_membuat_user_baru_dengan_role_customer()
    {
        session(['oauth_state' => 'test_state']);
        
        $userData = [
            'email' => 'john@example.com',
            'nama' => 'John Doe',
            'nik' => '1234567890123456',
            'tgl_lahir' => '1990-01-01',
            'rt' => 1,
            'rw' => 2,
            'kode_pos' => 12345,
            'alamat' => 'Jl. Test No. 123',
            'username' => 'johndoe',
            'no_wa' => '081234567890'
        ];

        Http::fake([
            'https://sso.example.com/token' => Http::response([
                'access_token' => 'test_access_token'
            ], 200),
            'https://sso.example.com/user' => Http::response($userData, 200)
        ]);

        $response = $this->get(route('sso.callback', [
            'code' => 'test_code',
            'state' => 'test_state'
        ]));

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'nama' => 'John Doe',
            'nik' => '1234567890123456'
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertTrue($user->hasRole('customer'));
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
        $this->assertEquals('test_access_token', session('access_token'));
        
        $response->assertRedirect(route('customer.dashboard'));
    }

    public function test_callback_memperbarui_user_yang_sudah_ada()
    {
        // Gunakan User factory untuk membuat user yang sudah ada
        $existingUser = User::factory()->create([
            'email' => 'john@example.com',
            'nama' => 'Old Name',
            'alamat' => 'Old Address',
            'username' => 'oldusername',
            'no_telepon' => '081234567890'
        ]);
        $existingUser->assignRole('customer');

        session(['oauth_state' => 'test_state']);
        
        $userData = [
            'email' => 'john@example.com',
            'nama' => 'John Updated',
            'nik' => $existingUser->nik, // Gunakan NIK dari factory
            'tgl_lahir' => $existingUser->tanggal_lahir,
            'rt' => 3,
            'rw' => 4,
            'kode_pos' => 54321,
            'alamat' => 'New Address',
            'username' => 'newusername',
            'no_wa' => '089876543210'
        ];

        Http::fake([
            'https://sso.example.com/token' => Http::response([
                'access_token' => 'test_access_token'
            ], 200),
            'https://sso.example.com/user' => Http::response($userData, 200)
        ]);

        $response = $this->get(route('sso.callback', [
            'code' => 'test_code',
            'state' => 'test_state'
        ]));

        $this->assertDatabaseHas('users', [
            'id' => $existingUser->id,
            'email' => 'john@example.com',
            'nama' => 'John Updated',
            'alamat' => 'New Address',
            'username' => 'newusername',
            'no_telepon' => '089876543210'
        ]);

        $response->assertRedirect(route('customer.dashboard'));
    }

    public function test_callback_mengarahkan_superadmin_ke_dashboard_pegawai()
    {
        $this->createUserWithRoleAndTest('superadmin', 'pegawai.dashboard');
    }

    public function test_callback_mengarahkan_admin_ke_dashboard_pegawai()
    {
        $this->createUserWithRoleAndTest('admin', 'pegawai.dashboard');
    }

    public function test_callback_mengarahkan_teknisi_ke_dashboard_pegawai()
    {
        $this->createUserWithRoleAndTest('teknisi', 'pegawai.dashboard');
    }

    public function test_callback_menangani_user_tanpa_username()
    {
        session(['oauth_state' => 'test_state']);
        
        $userData = [
            'email' => 'john@example.com',
            'nama' => 'John Doe',
            'nik' => '1234567890123456',
            'tgl_lahir' => '1990-01-01',
            'rt' => 1,
            'rw' => 2,
            'kode_pos' => 12345,
            'alamat' => 'Jl. Test No. 123',
            'no_wa' => '081234567890'
            // username is missing
        ];

        Http::fake([
            'https://sso.example.com/token' => Http::response([
                'access_token' => 'test_access_token'
            ], 200),
            'https://sso.example.com/user' => Http::response($userData, 200)
        ]);

        $response = $this->get(route('sso.callback', [
            'code' => 'test_code',
            'state' => 'test_state'
        ]));

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'username' => 'john@example.com' // Should fallback to email
        ]);

        $response->assertRedirect(route('customer.dashboard'));
    }

    public function test_logout_membersihkan_auth_dan_session()
    {
        // Gunakan User factory
        $user = User::factory()->create();

        Auth::login($user);
        session(['access_token' => 'test_access_token']);

        $this->assertTrue(Auth::check());
        $this->assertNotNull(session('access_token'));

        $response = $this->get(route('sso.logout'));

        $this->assertFalse(Auth::check());
        $this->assertNull(session('access_token'));
        $response->assertRedirect('/');
    }

    public function test_permintaan_http_dibuat_dengan_parameter_yang_benar()
    {
        session(['oauth_state' => 'test_state']);

        Http::fake([
            'https://sso.example.com/token' => Http::response([
                'access_token' => 'test_access_token'
            ], 200),
            'https://sso.example.com/user' => Http::response([
                'email' => 'john@example.com',
                'nama' => 'John Doe',
                'nik' => '1234567890123456',
                'tgl_lahir' => '1990-01-01',
                'rt' => 1,
                'rw' => 2,
                'kode_pos' => 12345,
                'alamat' => 'Jl. Test No. 123',
                'username' => 'johndoe',
                'no_wa' => '081234567890'
            ], 200)
        ]);

        $this->get(route('sso.callback', [
            'code' => 'test_code',
            'state' => 'test_state'
        ]));

        Http::assertSent(function (Request $request) {
            return $request->url() === 'https://sso.example.com/token' &&
                   $request['grant_type'] === 'authorization_code' &&
                   $request['client_id'] === 'test_client_id' &&
                   $request['client_secret'] === 'test_client_secret' &&
                   $request['code'] === 'test_code';
        });

        Http::assertSent(function (Request $request) {
            return $request->url() === 'https://sso.example.com/user' &&
                   $request->hasHeader('Authorization', 'Bearer test_access_token') &&
                   $request->hasHeader('Accept', 'application/json');
        });
    }

    private function createUserWithRoleAndTest(string $roleName, string $expectedRoute): void
    {
        // Gunakan User factory untuk membuat user
        $user = User::factory()->create([
            'email' => 'test@example.com'
        ]);
        $user->assignRole($roleName);

        session(['oauth_state' => 'test_state']);
        
        $userData = [
            'email' => 'test@example.com',
            'nama' => $user->nama,
            'nik' => $user->nik,
            'tgl_lahir' => $user->tanggal_lahir,
            'rt' => $user->rt,
            'rw' => $user->rw,
            'kode_pos' => $user->kode_pos,
            'alamat' => $user->alamat,
            'username' => $user->username,
            'no_wa' => $user->no_telepon
        ];

        Http::fake([
            'https://sso.example.com/token' => Http::response([
                'access_token' => 'test_access_token'
            ], 200),
            'https://sso.example.com/user' => Http::response($userData, 200)
        ]);

        $response = $this->get(route('sso.callback', [
            'code' => 'test_code',
            'state' => 'test_state'
        ]));

        $response->assertRedirect(route($expectedRoute));
    }
}
