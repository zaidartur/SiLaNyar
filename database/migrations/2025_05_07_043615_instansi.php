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
        Schema::create('instansi', function(Blueprint $table)
        {
            $table->id();
            $table->string('kode_instansi')->unique();
            $table->foreignId('id_customer')->constrained('customer')->onDelete('cascade');
            $table->string('nama');
            $table->enum('tipe', ['swasta', 'pemerintahan', 'pribadi']);
            $table->string('alamat');
            $table->string('no_telepon');
            $table->string('email')->unique();
            $table->enum('status_verifikasi', ['diproses', 'diterima', 'ditolak'])->default('diproses');
            $table->string('diverifikasi_oleh');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
