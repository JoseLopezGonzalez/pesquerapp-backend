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
        Schema::create('process_article', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('process_id');
            $table->unsignedBigInteger('article_id');
            $table->timestamps();
    
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_article');
    }
};
