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
        Schema::create('hasil_uji', function (Blueprint $table) {
            $table->id();
            $table->string('kode_hasil_uji')->unique();
            $table->foreignId('id_pengujian')->constrained('pengujian')->onDelete('cascade');
            $table->enum('status', ['draf', 'revisi', 'proses_review', 'proses_peresmian', 'selesai'])->default('draf');
            $table->timestamp('proses_review_at')->nullable();
            $table->string('file_pdf')->nullable();
            $table->string('diverifikasi_oleh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_uji');
    }
};
