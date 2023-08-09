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
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_palet')->nullable();
            $table->unsignedBigInteger('id_articulo');
            $table->string('lote');
            $table->string('GS1_128');
            $table->decimal('peso_bruto', 6, 2);
            $table->decimal('peso_neto', 6, 2);
            $table->foreign('id_palet')->references('id')->on('palets')->onDelete('set null');
            $table->foreign('id_articulo')->references('id')->on('articulos_materia_prima');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
