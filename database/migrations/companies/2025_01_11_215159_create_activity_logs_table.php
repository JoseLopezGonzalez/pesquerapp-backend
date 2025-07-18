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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('action'); // Acción realizada (login, logout, etc.)
            $table->string('ip_address')->nullable(); // Dirección IP
            $table->string('device')->nullable(); // Tipo de dispositivo
            $table->string('browser')->nullable(); // Navegador usado
            $table->string('location')->nullable(); // Ubicación (opcional)
            $table->text('details')->nullable(); // Detalles adicionales
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
