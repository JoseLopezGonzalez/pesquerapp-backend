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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_especie');
            $table->unsignedBigInteger('id_zona_captura');
            $table->bigInteger('GTIN');
            $table->bigInteger('GTIN_caja');
            $table->bigInteger('GTIN_palet');
            $table->decimal('peso_fijo', 6, 2);
            $table->foreign('id_especie')->references('id')->on('species');
            $table->foreign('id_zona_captura')->references('id')->on('capture_zones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
