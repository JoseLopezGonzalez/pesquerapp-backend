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
        Schema::create('cebo_dispatches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supllier_id');
            $table->date('date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('supllier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cebo_dispatches');
    }
};
