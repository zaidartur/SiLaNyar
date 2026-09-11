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
        Schema::create('kategori_subkategori', function (Blueprint $table)
        {
            $table->id();
            $table->foreignUuid('id_kategori')->constrained('kategori', 'uuid')->onDelete('cascade');
            $table->foreignUuid('id_subkategori')->constrained('subkategori', 'uuid')->onDelete('cascade');
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
