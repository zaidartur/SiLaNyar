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
            $table->string('kode_aduan')->unique();
            $table->foreignId('id_hasil_uji')->constrained('hasil_uji')->onDelete('cascade');
            $table->foreignId('id_customer')->constrained('customer')->onDelete('cascade');
            $table->string('masalah');
            $table->string('perbaikan');
            $table->enum('status', ['diterima', 'ditolak'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aduans');
    }
};
