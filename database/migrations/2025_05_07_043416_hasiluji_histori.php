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
        Schema::create('hasil_uji_histori', function(Blueprint $table)
        {
           $table->id();
           $table->foreignId('id_hasil_uji')->constrained('hasil_uji')->onDelete('cascade');
           $table->foreignId('id_parameter')->constrained('parameter_uji')->onDelete('cascade');
           $table->foreignId('id_pengujian')->constrained('pengujian')->onDelete('cascade');
           $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
           $table->float('nilai');
           $table->string('keterangan');
           $table->enum('status', ['acc', 'revisi', 'draf'])->default('draf');
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
