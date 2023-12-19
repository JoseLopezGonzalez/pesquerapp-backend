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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('vat_number');
            $table->foreignId('payment_term_id')->constrained(); // Clave foránea a payment_terms
            $table->text('billing_address');
            $table->text('shipping_address');
            $table->text('transportation_notes')->nullable();
            $table->text('production_notes')->nullable();
            $table->text('accounting_notes')->nullable();
            $table->foreignId('salesperson_id')->constrained(); // Clave foránea a salespersons
            $table->text('emails'); // Asumiendo que quieres almacenar varios emails en un campo JSON
            $table->text('contact_info');
            $table->foreignId('country_id')->constrained(); // Clave foránea a countries
            $table->foreignId('transport_id')->constrained(); // Clave foránea a transports

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
