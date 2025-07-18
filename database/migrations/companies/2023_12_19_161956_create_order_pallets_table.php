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
        Schema::create('order_pallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained(); // Clave foránea a la tabla orders
            $table->foreignId('pallet_id')->constrained('pallets'); // Clave foránea a la tabla pallets    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_pallets');
    }
};
