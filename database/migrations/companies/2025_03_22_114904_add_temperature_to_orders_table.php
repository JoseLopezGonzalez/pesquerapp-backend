<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_temperature_to_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemperatureToOrdersTable extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('temperature', 5, 2)->nullable()->after('trailer_plate');
            // (5,2) permite valores como -99.99 a 999.99
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('temperature');
        });
    }
}
