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
            $table->string('alamat_pribadi');
            $table->integer('kontak_pribadi');
            $table->string('nama_instansi');
            $table->string('tipe_instansi');
            $table->string('alamat_instansi');
            $table->integer('kontak_instansi');
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
