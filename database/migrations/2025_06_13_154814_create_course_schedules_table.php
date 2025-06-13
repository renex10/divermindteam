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
        Schema::create('course_schedules', function (Blueprint $table) {
 $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade'); 
            $table->time('start_time'); 
            $table->time('end_time'); 
            $table->timestamps();

            // Define horarios exactos de los cursos
            // Relación con la tabla 'courses' para asignar horarios
            // Hora de inicio y fin del curso


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_schedules');
    }
};
