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
        Schema::create('has_roles', function(Blueprint $table) {
            $table->id();
            $table->foreignId('id_role')->constrained('role')->onDelete('cascade');
            $table->foreignId('id_pegawai')->constrained('pegawai')->onDelete('cascade');
            $table->enum('status_verifikasi', ['proses', 'verifikasi', 'ditolak'])->default('proses');
            $table->foreignId('diverivifikasi_oleh')->nullable()->constrained('pegawai');
            $table->timestamp('email_verified_at')->nullable();
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
