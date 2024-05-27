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
        Schema::create('production_nodes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_id');
            $table->unsignedBigInteger('template_node_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
    
            $table->foreign('production_id')->references('id')->on('productions')->onDelete('cascade');
            $table->foreign('template_node_id')->references('id')->on('template_nodes')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('production_nodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_nodes');
    }
};
