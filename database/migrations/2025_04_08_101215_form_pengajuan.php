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
        Schema::create('form_pengajuan', function(Blueprint $table) {
            $table->id();
            $table->string('kode_pengajuan')->unique();
            $table->foreignId('id_instansi')->constrained('instansi')->onDelete('cascade');
            $table->foreignId('id_kategori')->nullable()->constrained('kategori')->onDelete('cascade');
            $table->foreignId('id_jenis_cairan')->constrained('jenis_cairan')->onDelete('cascade');
            $table->float('volume_sampel')->check('volume_sampel >= 0');
            $table->enum('status_pengajuan', ['proses_validasi', 'diterima', 'ditolak'])
                  ->default('proses_validasi')
                  ->index();
            $table->enum('metode_pengambilan', ['diantar', 'diambil']);
            $table->string('lokasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_pengajuan');
    }
};
