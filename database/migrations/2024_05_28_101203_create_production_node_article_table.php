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
        Schema::create('production_node_article', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_node_id');
            $table->unsignedBigInteger('article_id');
            $table->integer('quantity');
            $table->timestamps();
    
            $table->foreign('production_node_id')->references('id')->on('production_nodes')->onDelete('cascade');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_node_article');
    }
};
