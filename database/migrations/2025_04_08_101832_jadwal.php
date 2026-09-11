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
        Schema::create('jadwal', function(Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode_pengambilan')->unique();
            $table->foreignUuid('id_form_pengajuan')->constrained('form_pengajuan', 'uuid')->onDelete('cascade');
            $table->foreignUuid('id_user')->constrained('users', 'uuid')->onDelete('cascade');
            $table->date('waktu_pengambilan');
            $table->enum('status', ['diproses', 'diterima'])->default('diproses');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
