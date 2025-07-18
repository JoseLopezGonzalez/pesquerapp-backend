<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cebo_dispatches', function (Blueprint $table) {
            $table->string('export_type')->default('facilcom')->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('cebo_dispatches', function (Blueprint $table) {
            $table->dropColumn('export_type');
        });
    }
};
