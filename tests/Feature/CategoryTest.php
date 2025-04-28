<?php

namespace Tests\Feature;

use App\Models\kategori;
use App\Models\parameter_uji;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;  // Tambahkan use statement ini di bagian atas file

class CategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $parameter;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->parameter = parameter_uji::factory()->create();
    }

    public function test_can_display_category_list(): void
    {
        // Create some test categories
        kategori::factory()->count(3)->create();

        $response = $this->get(route('test.kategori.index'));

        $response->assertStatus(200);
        $response->assertViewIs('test.kategori.index');
        $response->assertViewHas('kategori');
    }

    public function test_can_create_new_category(): void
    {
        $parameters = parameter_uji::factory()->count(2)->create();
        
        $categoryData = [
            'nama' => $this->faker->unique()->word(),
            'harga' => $this->faker->numberBetween(50000, 500000),
            'parameter_ids' => $parameters->pluck('id')->toArray()
        ];

        $response = $this->post('/kategori/store', $categoryData);

        $response->assertRedirect(route('kategori.index'));
        $this->assertDatabaseHas('kategori', [
            'nama' => $categoryData['nama'],
            'harga' => $categoryData['harga']
        ]);

        // Check if parameters were attached
        $category = kategori::where('nama', $categoryData['nama'])->first();
        $this->assertEquals(2, $category->parameter()->count());
    }

    public function test_can_show_edit_category_form(): void
    {
        $category = kategori::factory()->create();

        $response = $this->get("/test/kategori/{$category->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('test.kategori.edit');
        $response->assertViewHas('kategori');
    }

    public function test_can_update_category(): void
    {
        $category = kategori::factory()->create();
        $parameters = parameter_uji::factory()->count(2)->create();

        $updatedData = [
            'nama' => 'Updated Category Name',
            'harga' => 75000,
            'parameter_ids' => $parameters->pluck('id')->toArray()
        ];

        $response = $this->put("/kategori/edit/{$category->id}", $updatedData);

        $response->assertRedirect(route('kategori.index'));
        $this->assertDatabaseHas('kategori', [
            'id' => $category->id,
            'nama' => $updatedData['nama'],
            'harga' => $updatedData['harga']
        ]);
        
        // Verify parameters were updated
        $this->assertEquals(2, $category->fresh()->parameter()->count());
    }

    public function test_can_delete_category(): void
    {
        $category = kategori::factory()->create();

        $response = $this->delete("/kategori/{$category->id}");

        $response->assertRedirect(route('kategori.index'));
        $this->assertDatabaseMissing('kategori', ['id' => $category->id]);
    }

    public function test_validates_required_fields_on_create(): void
    {
        $response = $this->post('/kategori/store', []);

        $response->assertSessionHasErrors([
            'nama',
            'harga',
            'parameter_ids'
        ]);
    }

    public function test_validates_numeric_price(): void
    {
        $categoryData = [
            'id_parameter' => $this->parameter->id,
            'nama' => 'Test Category',
            'harga' => 'invalid_price'
        ];

        $response = $this->post('/kategori/store', $categoryData);

        $response->assertSessionHasErrors('harga');
    }

    public function test_validates_minimum_price(): void
    {
        $categoryData = [
            'id_parameter' => $this->parameter->id,
            'nama' => 'Test Category',
            'harga' => -100
        ];

        $response = $this->post('/kategori/store', $categoryData);

        $response->assertSessionHasErrors('harga');
    }

    public function test_category_name_must_be_unique(): void
    {
        $existingCategory = kategori::factory()->create();
        $parameters = parameter_uji::factory()->count(2)->create();

        $categoryData = [
            'nama' => $existingCategory->nama,
            'harga' => 100000,
            'parameter_ids' => $parameters->pluck('id')->toArray()
        ];

        $response = $this->post('/kategori/store', $categoryData);

        $response->assertSessionHasErrors('nama');
    }

    public function test_can_show_category_details(): void
    {
        $category = kategori::factory()->create();
        $parameters = parameter_uji::factory()->count(2)->create();
        $category->parameter()->attach($parameters->pluck('id'));

        $response = $this->get("/kategori/{$category->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('kategori/show')
            ->has('kategori', fn ($kategori) => $kategori
                ->where('id', $category->id)
                ->where('nama', $category->nama)
                ->where('harga', $category->harga)
                ->has('parameter', 2)
            )
        );
    }

    public function test_category_requires_valid_parameter(): void
    {
        $categoryData = [
            'nama' => 'Test Category',
            'harga' => 100000,
            'parameter_ids' => [999999] // Non-existent parameter ID
        ];

        $response = $this->post('/kategori/store', $categoryData);

        $response->assertSessionHasErrors('parameter_ids.0');
        // atau bisa juga menggunakan:
        // $response->assertSessionHasErrors(['parameter_ids.0' => 'The selected parameter_ids.0 is invalid.']);
    }

    public function test_can_assign_multiple_parameters_to_category(): void
    {
        $category = kategori::factory()->create();
        $parameters = parameter_uji::factory()->count(3)->create();

        $category->parameter()->attach($parameters->pluck('id'));

        $this->assertEquals(3, $category->parameter()->count());
    }

    public function test_deleting_category_removes_parameter_relationships(): void
    {
        $category = kategori::factory()->create();
        $parameters = parameter_uji::factory()->count(2)->create();
        $category->parameter()->attach($parameters->pluck('id'));

        $category->delete();

        $this->assertDatabaseMissing('parameter_kategori', ['id_kategori' => $category->id]);
    }
}
