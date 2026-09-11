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
        Schema::create('parameter_pengajuan', function(Blueprint $table)
        {
           $table->id();
           $table->foreignUuid('id_parameter')->constrained('parameter_uji', 'uuid')->onDelete('cascade');
           $table->foreignUuid('id_pengajuan')->constrained('form_pengajuan', 'uuid')->onDelete('cascade');
           
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
