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
            $table->foreignId('id_customer')->constrained('customer')->onDelete('cascade');
            $table->foreignId('id_kategori')->nullable()->constrained('kategori')->onDelete('cascade');
            $table->foreignId('id_jenis_cairan')->constrained('jenis_cairan')->onDelete('cascade');
            $table->float('volume_sampel');
            $table->enum('status_pengajuan', ['proses_validasi', 'diterima', 'ditolak'])->default('proses_validasi')->index();
            $table->enum('metode_pengambilan', ['diantar', 'diambil']);
            $table->string('lokasi');
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
