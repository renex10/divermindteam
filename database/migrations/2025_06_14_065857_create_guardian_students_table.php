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
        Schema::create('guardian_students', function (Blueprint $table) {
            $table->id();

            // Relación con estudiantes
            $table->foreignId('student_id')
                ->constrained('students')
                ->onDelete('cascade');

            // Relación con usuarios (como apoderados)
            $table->foreignId('guardian_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->boolean('is_primary')->default(false)
                ->comment('Indica si es el apoderado principal');

            $table->string('relationship', 50)
                ->comment('Parentesco: padre, madre, tutor, etc.');

            $table->timestamps();

            // Evitar relaciones duplicadas
            $table->unique(['student_id', 'guardian_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardian_students');
    }
};
