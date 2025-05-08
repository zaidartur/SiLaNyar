<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Support\Facades\Auth;

class ProfileCustomerTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;

    public function setUp(): void
    {
        parent::setUp();
        
        // Buat customer yang sudah terverifikasi
        $this->customer = Customer::factory()->create([
            'password' => Hash::make('password123'),
            'status_verifikasi' => 'diterima',
            'email_verified_at' => now()
        ]);
    }

    // public function test_customer_bisa_melihat_halaman_profil()
    // {
    //     $this->withoutExceptionHandling(); // Untuk melihat error lebih detail

    //     $response = $this->actingAs($this->customer, 'customer')
    //         ->get(route('customer.profile'));

    //     $response->assertStatus(200);
    //     $response->assertInertia(function (Assert $page) {
    //         return $page
    //             ->component('customer/profile/show')
    //             ->has('customer', function (Assert $customer) {
    //                 return $customer
    //                     ->has('id')
    //                     ->has('nama')
    //                     ->has('email')
    //                     ->etc();
    //             });
    //     });
    // }

    public function test_customer_bisa_mengupdate_profil()
    {
        $updateData = [
            'nama' => 'Nama Baru',
            'jenis_user' => 'perorangan',
            'alamat_pribadi' => 'Alamat Baru',
            'kontak_pribadi' => '+6281234567890',
            'email' => 'email.baru@example.com'
        ];

        $response = $this->actingAs($this->customer, 'customer')
            ->put('/customer/profile/update', $updateData); // Gunakan PUT method

        $this->customer->refresh();

        // Tambahkan assertion untuk response
        $response->assertRedirect(route('customer.profile'));
        
        // Verifikasi perubahan data
        $this->assertEquals($updateData['nama'], $this->customer->fresh()->nama);
        $this->assertEquals($updateData['kontak_pribadi'], $this->customer->fresh()->kontak_pribadi);
    }

    public function test_validasi_data_profil_customer()
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->from('/customer/profile/edit')  // Tambahkan ini untuk redirect back
            ->put('/customer/profile/update', [
                'nama' => '',  // nama kosong
                'jenis_user' => 'perorangan',
                'kontak_pribadi' => 'bukan-nomor-telepon',  // format salah
                'email' => 'bukan-email'  // format email salah
            ]);

        $response->assertRedirect()
                ->assertSessionHasErrors([
                    'nama',
                    'kontak_pribadi',
                    'email'
                ]);
    }

    public function test_customer_instansi_harus_isi_data_instansi()
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->from('/customer/profile/edit')  // Tambahkan ini untuk redirect back
            ->put('/customer/profile/update', [
                'nama' => 'Nama Customer',
                'jenis_user' => 'instansi',
                'kontak_pribadi' => '+6281234567890',
                'email' => 'email@example.com',
                'alamat_pribadi' => 'Alamat Test',
                // Kosongkan data instansi untuk memicu error
                'nama_instansi' => '',
                'tipe_instansi' => '',
                'alamat_instansi' => '',
                'kontak_instansi' => ''
            ]);

        $response->assertInvalid([
            'nama_instansi',
            'tipe_instansi',
            'alamat_instansi',
            'kontak_instansi'
        ]);
    }

    public function test_customer_bisa_menghapus_akun()
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->delete(route('customer.profile.destroy'), [
                'password' => 'password123'
            ]);

        $this->assertNull(Customer::find($this->customer->id));
        $this->assertGuest('customer');
    }

    public function test_password_harus_benar_untuk_hapus_akun()
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->from('/customer/profile/show')
            ->delete(route('customer.profile.destroy'), [
                'password' => 'password_salah' // Password yang salah
            ]);

        // Memastikan error muncul
        $response->assertSessionHasErrors('password');
        
        // Memastikan akun masih ada di database
        $this->assertDatabaseHas('customer', [
            'id' => $this->customer->id
        ]);
        
        // Memastikan user masih terautentikasi
        $this->assertTrue(Auth::guard('customer')->check());
    }
}
