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
        Schema::create('customer', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis_user', ["instansi", "perorangan"]);
            $table->string('alamat_pribadi')->nullable();
            $table->string('kontak_pribadi');
            $table->string('nama_instansi')->nullable();
            $table->enum('tipe_instansi', ['swasta', 'pemerintahan'])->nullable();
            $table->string('alamat_instansi')->nullable();
            $table->string('kontak_instansi')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('status_verifikasi', ['diproses', 'diterima', 'ditolak'])->default('diproses');
            $table->rememberToken();
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
