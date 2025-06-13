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
        Schema::create('courses', function (Blueprint $table) {
   $table->id();
            $table->foreignId('establishment_id')->constrained()->onDelete('cascade'); 
            $table->string('name', 50); 
            $table->enum('level', ['kinder', 'basic', 'high']); 
            $table->enum('shift', ['morning', 'afternoon']); 
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null'); 
            $table->timestamps();

            // Tabla de cursos académicos en cada establecimiento
            // Nombre del curso, ejemplo: "4° Básico A"
            // Nivel educativo (kinder, básica, media)
            // Jornada del curso (mañana o tarde)
            // Profesor jefe asignado, referenciado a la tabla 'users'
            // Si el profesor es eliminado, su asignación se pone en NULL sin afectar el curso
            // Timestamps para seguimiento de cambios


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
