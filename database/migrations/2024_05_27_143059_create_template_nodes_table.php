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
        Schema::create('template_nodes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('process_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
    
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('template_nodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_nodes');
    }
};
