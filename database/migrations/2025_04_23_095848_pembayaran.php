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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('id_order')->unique();
            $table->foreignId('id_form_pengajuan')->constrained('form_pengajuan')->onDelete('cascade');
            $table->integer('total_biaya');
            $table->date('tanggal_pembayaran')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->string('status_pembayaran');
            $table->string('bukti_pembayaran')->nullable();
            $table->string('id_transaksi')->nullable();
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
