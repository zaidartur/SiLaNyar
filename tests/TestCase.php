<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        
        // Key generation removed - use fixed key from .env.testing
    }

    protected static array $uuidForeignKeyMap = [
        'id_user' => \App\Models\User::class,
        'diverifikasi_oleh' => \App\Models\User::class,
        'id_instansi' => \App\Models\Instansi::class,
        'id_kategori' => \App\Models\Kategori::class,
        'id_jenis_cairan' => \App\Models\JenisCairan::class,
        'id_form_pengajuan' => \App\Models\FormPengajuan::class,
        'id_pengujian' => \App\Models\Pengujian::class,
        'id_hasil_uji' => \App\Models\HasilUji::class,
        'id_parameter' => \App\Models\ParameterUji::class,
        'id_subkategori' => \App\Models\SubKategori::class,
    ];

    protected function normalizeDatabaseData(array $data): array
    {
        foreach (static::$uuidForeignKeyMap as $column => $class) {
            if (isset($data[$column]) && is_numeric($data[$column])) {
                $uuid = $class::where('id', $data[$column])->value('uuid');
                if ($uuid) {
                    $data[$column] = $uuid;
                }
            }
        }
        return $data;
    }

    public function assertDatabaseHas($table, array $data = [], $connection = null)
    {
        return parent::assertDatabaseHas($table, $this->normalizeDatabaseData($data), $connection);
    }

    public function assertDatabaseMissing($table, array $data = [], $connection = null)
    {
        return parent::assertDatabaseMissing($table, $this->normalizeDatabaseData($data), $connection);
    }

    protected function assertRedirectContains($response, $needle)
    {
        $this->assertTrue(
            str_contains($response->headers->get('Location'), $needle),
            "Expected redirect URL to contain '{$needle}', but got: " . $response->headers->get('Location')
        );
        
        return $this;
    }
}
