<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('instansi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_instansi')->unique();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->string('nama');
            $table->enum('tipe', ['swasta', 'pemerintahan', 'pribadi']);
            $table->string('alamat');
            $table->string('wilayah');
            $table->string('desa_kelurahan');
            $table->string('email')->unique();
            $table->string('no_telepon');
            $table->string('posisi_jabatan');
            $table->string('departemen_divisi');
            $table->enum('status_verifikasi', ['diproses', 'diterima', 'ditolak'])->default('diproses');
            $table->string('diverifikasi_oleh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instansi');
    }
};
