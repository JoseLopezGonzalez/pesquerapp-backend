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
        Schema::create('productions', function (Blueprint $table) {
            $table->id(); // ID de la producción
            $table->string('lot')->nullable(); // Lote
            $table->date('date')->nullable(); // Fecha
            $table->foreignId('species_id')->constrained('species')->onDelete('cascade'); // Clave foránea a species
            $table->foreignId('capture_zone_id')->constrained('capture_zones')->onDelete('cascade'); // Clave foránea a capture_zones
            $table->json('diagram_data'); // JSON del diagrama
            $table->timestamps(); // Marcas de tiempo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
