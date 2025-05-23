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
        Schema::create('parameter_subkategori', function (Blueprint $table)
        {
           $table->id();
           $table->foreignId('id_parameter')->constrained('parameter_uji')->onDelete('cascade');
           $table->foreignId('id_subkategori')->constrained('subkategori')->onDelete('cascade');
           $table->string('baku_mutu');
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
