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
            $table->foreignId('id_pembayaran')->constrained('pembayaran')->onDelete('cascade');
            $table->foreignId('id_kategori')->constrained('kategori')->onDelete('cascade');
            $table->string('deskripsi');
            $table->enum('status_pengujian', ['diproses', 'selesai', 'batal']);
            $table->date('tanggal_terima');
            $table->enum('metode_pengambilan', ['teknisi', 'customer']);
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
