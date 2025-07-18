<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::rename('order_details', 'order_planned_product_details');
    }

    public function down(): void
    {
        Schema::rename('order_planned_product_details', 'order_details');
    }
};
