<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
 // Estudiantes (CAMBIOS CLAVE)
Schema::create('students', function (Blueprint $table) {
    $table->engine = 'InnoDB';
    $table->id();

    // Relación con usuarios
    $table->foreignId('user_id')
        ->constrained() // Eliminado nullable()
        ->onDelete('cascade'); // Borrar estudiante si se borra usuario

    // Nuevo campo para establecimiento
    $table->foreignId('establishment_id')
        ->constrained('establishments')
        ->onDelete('cascade'); // Borrar estudiante si se borra establecimiento

    $table->foreignId('assigned_specialist_id')
        ->nullable()
        ->constrained('professionals') // Relación con profesionales
        ->onDelete('set null'); // Si se elimina el profesional, se establece null
    // ... resto de campos ...

    // Datos exclusivos del estudiante
    $table->date('birth_date');
    $table->enum('need_type', ['permanent', 'temporary'])->default('temporary');
    $table->text('diagnosis')->nullable();
    $table->tinyInteger('priority')->default(3)->comment('1: Máxima, 2: Media, 3: Básica');
    $table->boolean('active')->default(true);
    $table->timestamps();

    // Índices
    $table->index('priority');
    $table->index('active');
});

    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};