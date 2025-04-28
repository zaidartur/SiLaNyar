<?php

namespace Tests\Feature;

use App\Models\form_pengajuan;
use App\Models\jadwal;
use App\Models\pegawai;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;

class ScheduleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $pegawai;
    private $formPengajuan;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->pegawai = pegawai::factory()->create();
        $this->formPengajuan = form_pengajuan::factory()->create();
    }

    public function test_can_display_schedule_list(): void
    {
        // Create some test schedules
        jadwal::factory()->count(3)->create();

        $response = $this->get(route('test.jadwal.index'));

        $response->assertStatus(200);
        $response->assertViewIs('test.jadwal.index');
        $response->assertViewHas('jadwal');
    }

    public function test_can_create_new_schedule(): void
    {
        $scheduleData = [
            'id_form_pengajuan' => $this->formPengajuan->id,
            'id_pegawai' => $this->pegawai->id,
            'waktu_pengambilan' => now()->addDays(2)->toDateString(),
            'status' => 'diproses',
            'keterangan' => 'Test keterangan'
        ];

        $response = $this->post('/jadwal/store', $scheduleData);

        $response->assertRedirect(route('jadwal.index'));
        $this->assertDatabaseHas('jadwal', $scheduleData);
    }

    public function test_can_show_edit_schedule_form(): void
    {
        $jadwal = jadwal::factory()->create();

        $response = $this->get("/test/jadwal/{$jadwal->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('test.jadwal.edit');
        $response->assertViewHas(['jadwal', 'form_pengajuan']);
    }

    public function test_can_update_schedule(): void
    {
        $jadwal = jadwal::factory()->create();

        $updatedData = [
            'id_form_pengajuan' => $this->formPengajuan->id,
            'waktu_pengambilan' => now()->addDays(3)->toDateString(),
            'status' => 'selesai',
            'keterangan' => 'Updated keterangan'
        ];

        $response = $this->put("/jadwal/edit/{$jadwal->id}", $updatedData);

        $response->assertRedirect(route('test.jadwal.index'));
        $this->assertDatabaseHas('jadwal', $updatedData);
    }

    public function test_can_delete_schedule(): void
    {
        $jadwal = jadwal::factory()->create();

        $response = $this->delete("/jadwal/{$jadwal->id}");

        $response->assertRedirect(route('test.jadwal.index'));
        $this->assertDatabaseMissing('jadwal', ['id' => $jadwal->id]);
    }

    public function test_validates_required_fields_on_create(): void
    {
        $response = $this->post('/jadwal/store', []);

        $response->assertSessionHasErrors([
            'id_form_pengajuan',
            'waktu_pengambilan',
            'status',
            'keterangan'
        ]);
    }

    public function test_validates_status_enum_values(): void
    {
        $scheduleData = [
            'id_form_pengajuan' => $this->formPengajuan->id,
            'id_pegawai' => $this->pegawai->id,
            'waktu_pengambilan' => now()->addDays(2),
            'status' => 'invalid_status',
            'keterangan' => 'Test keterangan'
        ];

        $response = $this->post('/jadwal/store', $scheduleData);

        $response->assertSessionHasErrors('status');
    }

    public function test_cannot_create_schedule_with_past_date(): void
    {
        $scheduleData = [
            'id_form_pengajuan' => $this->formPengajuan->id,
            'id_pegawai' => $this->pegawai->id,
            'waktu_pengambilan' => now()->subDays(1)->toDateString(),
            'status' => 'diproses',
            'keterangan' => 'Test keterangan'
        ];

        $response = $this->post('/jadwal/store', $scheduleData);
        $response->assertSessionHasErrors('waktu_pengambilan');
    }

    public function test_cannot_create_duplicate_schedule_for_same_form(): void
    {
        $existingSchedule = jadwal::factory()->create([
            'id_form_pengajuan' => $this->formPengajuan->id
        ]);

        $scheduleData = [
            'id_form_pengajuan' => $this->formPengajuan->id,
            'id_pegawai' => $this->pegawai->id,
            'waktu_pengambilan' => now()->addDays(2)->toDateString(),
            'status' => 'diproses',
            'keterangan' => 'Test keterangan'
        ];

        $response = $this->post('/jadwal/store', $scheduleData);
        $response->assertSessionHasErrors('id_form_pengajuan');
    }

    public function test_can_filter_schedules_by_status(): void
    {
        jadwal::factory()->create(['status' => 'diproses']);
        jadwal::factory()->create(['status' => 'selesai']);

        $response = $this->get('/jadwal?status=diproses');
        $response->assertViewHas('jadwal', function($jadwal) {
            return $jadwal->where('status', 'diproses')->count() === 1;
        });
    }

    public function test_can_filter_schedules_by_date_range(): void
    {
        jadwal::factory()->create([
            'waktu_pengambilan' => now()->addDays(1)
        ]);
        jadwal::factory()->create([
            'waktu_pengambilan' => now()->addDays(5)
        ]);

        $response = $this->get('/jadwal?start_date=' . now()->toDateString() . '&end_date=' . now()->addDays(2)->toDateString());
        $response->assertViewHas('jadwal', function($jadwal) {
            return $jadwal->count() === 1;
        });
    }

    public function test_can_search_schedules_by_keterangan(): void
    {
        jadwal::factory()->create(['keterangan' => 'Test ABC']);
        jadwal::factory()->create(['keterangan' => 'Test XYZ']);

        $response = $this->get('/jadwal?search=ABC');
        $response->assertViewHas('jadwal', function($jadwal) {
            return $jadwal->count() === 1 && $jadwal->first()->keterangan === 'Test ABC';
        });
    }

    public function test_validates_max_schedules_per_day(): void
    {
        // Create max allowed schedules for the day
        jadwal::factory()->count(5)->create([
            'waktu_pengambilan' => now()->addDays(1)
        ]);

        $scheduleData = [
            'id_form_pengajuan' => $this->formPengajuan->id,
            'id_pegawai' => $this->pegawai->id,
            'waktu_pengambilan' => now()->addDays(1),
            'status' => 'diproses',
            'keterangan' => 'Test keterangan'
        ];

        $response = $this->post('/jadwal/store', $scheduleData);
        $response->assertSessionHasErrors('waktu_pengambilan');
    }

    public function test_can_bulk_update_schedule_status(): void
    {
        $schedules = jadwal::factory()->count(3)->create(['status' => 'diproses']);
        
        $response = $this->put('/jadwal/bulk-update', [
            'ids' => $schedules->pluck('id')->toArray(),
            'status' => 'selesai'
        ]);

        $response->assertRedirect(route('test.jadwal.index'));
        $this->assertEquals(0, jadwal::where('status', 'diproses')->count());
        $this->assertEquals(3, jadwal::where('status', 'selesai')->count());
    }

    public function test_can_reschedule_within_allowed_timeframe(): void
    {
        $jadwal = jadwal::factory()->create([
            'waktu_pengambilan' => now()->addDays(5)
        ]);

        $updatedData = [
            'id_form_pengajuan' => $jadwal->id_form_pengajuan,
            'waktu_pengambilan' => now()->addDays(6),
            'status' => $jadwal->status,
            'keterangan' => 'Reschedule test'
        ];

        $response = $this->put("/jadwal/edit/{$jadwal->id}", $updatedData);
        $response->assertRedirect(route('test.jadwal.index'));
        $this->assertDatabaseHas('jadwal', $updatedData);
    }

    public function test_cannot_reschedule_completed_schedule(): void
    {
        $jadwal = jadwal::factory()->create([
            'status' => 'selesai'
        ]);

        $updatedData = [
            'waktu_pengambilan' => now()->addDays(1),
            'status' => 'diproses',
            'keterangan' => 'Should not update'
        ];

        $response = $this->put("/jadwal/edit/{$jadwal->id}", $updatedData);
        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('jadwal', ['id' => $jadwal->id, 'status' => 'selesai']);
    }
}
