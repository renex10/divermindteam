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
    // Establecimientos
    Schema::create('establishments', function (Blueprint $table) {
        $table->id();

        // Nombre oficial del establecimiento
        $table->string('name', 100)->comment('Official name of the educational establishment');

        // Dirección física completa
        $table->string('address', 200)->comment('Full physical address');

        // Relación con comuna
        $table->foreignId('commune_id')
              ->constrained()
              ->onDelete('restrict')
              ->onUpdate('cascade')
              ->comment('Commune where the establishment is located');

        // Cupo máximo para estudiantes PIE
        $table->integer('pie_quota_max')
              ->default(0)
              ->comment('Maximum number of students who can join the PIE program');

        // Estado del establecimiento: activo o inactivo
        $table->boolean('is_active')
              ->default(true)
              ->comment('Indicates if the establishment is active (true) or inactive (false)');

        // Timestamps de Laravel: created_at y updated_at
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establishments');
    }
};
