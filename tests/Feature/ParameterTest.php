<?php

namespace Tests\Feature;

use App\Models\parameter_uji;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class ParameterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_display_parameter_list(): void
    {
        parameter_uji::factory()->count(3)->create();

        $response = $this->get(route('parameter.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('parameter/index')
            ->has('parameter', 3)
        );
    }

    public function test_can_create_new_parameter(): void
    {
        $parameterData = [
            'nama_parameter' => $this->faker->unique()->word(),
            'satuan' => 'mg/L',
            'baku_mutu' => $this->faker->randomFloat(2, 0.01, 100),
            'harga' => $this->faker->numberBetween(50000, 500000)
        ];

        $response = $this->post('/parameter/store', $parameterData);

        $response->assertRedirect(route('parameter.index'));
        $this->assertDatabaseHas('parameter_uji', [
            'nama_parameter' => $parameterData['nama_parameter'],
            'satuan' => $parameterData['satuan'],
            'baku_mutu' => $parameterData['baku_mutu'],
            'harga' => $parameterData['harga']
        ]);
    }

    public function test_can_show_edit_parameter_form(): void
    {
        $parameter = parameter_uji::factory()->create();

        $response = $this->get("/parameter/edit/{$parameter->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('parameter/edit')
            ->has('parameter')
        );
    }

    public function test_can_update_parameter(): void
    {
        $parameter = parameter_uji::factory()->create();

        $updatedData = [
            'nama_parameter' => 'Updated Parameter Name',
            'satuan' => 'µg/L',
            'baku_mutu' => 50.5,
            'harga' => 75000
        ];

        $response = $this->put("/parameter/{$parameter->id}/edit", $updatedData);

        $response->assertRedirect(route('parameter.index'));
        $this->assertDatabaseHas('parameter_uji', [
            'id' => $parameter->id,
            'nama_parameter' => $updatedData['nama_parameter'],
            'satuan' => $updatedData['satuan'],
            'baku_mutu' => $updatedData['baku_mutu'],
            'harga' => $updatedData['harga']
        ]);
    }

    public function test_can_delete_parameter(): void
    {
        $parameter = parameter_uji::factory()->create();

        $response = $this->delete("/parameter/{$parameter->id}");

        $response->assertRedirect(route('parameter.index'));
        $this->assertDatabaseMissing('parameter_uji', ['id' => $parameter->id]);
    }

    public function test_validates_required_fields_on_create(): void
    {
        $response = $this->post('/parameter/store', []);

        $response->assertSessionHasErrors([
            'nama_parameter',
            'satuan',
            'baku_mutu',
            'harga'
        ]);
    }

    public function test_validates_numeric_harga(): void
    {
        $parameterData = [
            'nama_parameter' => 'Test Parameter',
            'satuan' => 'mg/L',
            'baku_mutu' => 50.5,
            'harga' => 'invalid_price'
        ];

        $response = $this->post('/parameter/store', $parameterData);

        $response->assertSessionHasErrors('harga');
    }

    public function test_validates_minimum_harga(): void
    {
        $parameterData = [
            'nama_parameter' => 'Test Parameter',
            'satuan' => 'mg/L',
            'baku_mutu' => 50.5,
            'harga' => -100
        ];

        $response = $this->post('/parameter/store', $parameterData);

        $response->assertSessionHasErrors('harga');
    }

    public function test_can_show_create_parameter_form(): void
    {
        $response = $this->get('/parameter/create');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('parameter/create')
        );
    }

    public function test_parameter_can_be_used_in_kategori(): void
    {
        $parameter = parameter_uji::factory()->create();
        $this->assertNotNull($parameter->kategori());
    }

    public function test_parameter_can_be_used_in_form_pengajuan(): void
    {
        $parameter = parameter_uji::factory()->create();
        $this->assertNotNull($parameter->form_pengajuan());
    }

    public function test_parameter_can_have_hasil_uji(): void
    {
        $parameter = parameter_uji::factory()->create();
        $this->assertNotNull($parameter->hasil_uji());
    }
}
