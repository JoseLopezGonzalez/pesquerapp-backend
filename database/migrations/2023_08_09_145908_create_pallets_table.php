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
            $table->text('observations');
            $table->unsignedBigInteger('state_id');
            //$table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('state_id')->references('id')->on('pallet_states');
            //$table->foreign('store_id')->references('id')->on('stores')->onDelete('set null');    
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
