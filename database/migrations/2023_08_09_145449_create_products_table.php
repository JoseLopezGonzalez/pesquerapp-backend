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
            $table->unsignedBigInteger('species_id');
            $table->unsignedBigInteger('capture_zone_id');
            $table->bigInteger('article_gtin');
            $table->bigInteger('box_gtin');
            $table->bigInteger('pallet_gtin');
            $table->decimal('fixed_weight', 6, 2);
            $table->foreign('species_id')->references('id')->on('species');
            $table->foreign('capture_zone_id')->references('id')->on('capture_zones');
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
