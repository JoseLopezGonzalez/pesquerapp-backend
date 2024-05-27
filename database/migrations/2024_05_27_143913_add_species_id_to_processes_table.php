<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('processes', function (Blueprint $table) {
            $table->unsignedBigInteger('species_id');

            $table->foreign('species_id')->references('id')->on('species')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('processes', function (Blueprint $table) {
            $table->dropForeign(['species_id']);
            $table->dropColumn('species_id');
        });
    }
};
