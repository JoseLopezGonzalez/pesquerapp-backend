<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_plates_to_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlatesToOrdersTable extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('truck_plate')->nullable()->after('shipping_address');
            $table->string('trailer_plate')->nullable()->after('truck_plate');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['truck_plate', 'trailer_plate']);
        });
    }
}
