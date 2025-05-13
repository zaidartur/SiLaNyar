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
        Schema::create('pengujian', function(Blueprint $table) {
            $table->id();
            $table->string('kode_pengujian')->unique();
            $table->foreignId('id_form_pengajuan')->constrained('form_pengajuan')->onDelete('cascade');
            $table->foreignId('id_pegawai')->constrained('pegawai')->onDelete('cascade');
            $table->foreignId('id_kategori')->constrained('kategori')->onDelete('cascade');
            $table->date('tanggal_uji');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status', ['diproses', 'selesai'])->default('diproses');
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
