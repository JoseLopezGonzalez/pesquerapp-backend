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
        Schema::create('pallets', function (Blueprint $table) {
            $table->id();
            $table->text('observaciones');
            $table->unsignedBigInteger('id_estado');
            $table->unsignedBigInteger('id_almacen')->nullable();
            $table->foreign('id_estado')->references('id')->on('pallet_states');
            $table->foreign('id_almacen')->references('id')->on('stores')->onDelete('set null');    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pallets');
    }
};
