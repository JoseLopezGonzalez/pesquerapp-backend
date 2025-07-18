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
        Schema::create('incoterms', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10); // Código del Incoterm, como "FOB", "CIF", etc.
            $table->text('description'); // Descripción del Incoterm
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoterms');
    }
};
