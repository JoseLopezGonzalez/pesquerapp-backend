<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('a3erp_cebo_code')->nullable()->after('facil_com_code');
            $table->string('facilcom_cebo_code')->nullable()->after('a3erp_cebo_code');
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['a3erp_cebo_code', 'facilcom_cebo_code']);
        });
    }
};
