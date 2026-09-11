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
        Schema::create('aduan', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode_aduan')->unique();
            $table->foreignUuid('id_hasil_uji')->constrained('hasil_uji', 'uuid')->onDelete('cascade');
            $table->foreignUuid('id_user')->constrained('users', 'uuid')->onDelete('cascade');
            $table->enum('terkait', ['administrasi', 'pengujian'])->nullable();
            $table->string('masalah');
            $table->string('perbaikan');
            $table->enum('status', ['diterima_administrasi', 'diterima_pengujian','ditolak', 'diproses'])->nullable();
            $table->string('diverifikasi_oleh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aduan');
    }
};
