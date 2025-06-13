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
        Schema::create('students', function (Blueprint $table) {
      $table->id();
            $table->foreignId('document_id')->nullable()->constrained('user_documents')->onDelete('set null'); 
            $table->string('first_name', 100); 
            $table->string('last_name', 100); 
            $table->date('birth_date'); 
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('set null'); 
            $table->enum('need_type', ['permanent', 'temporary']); 
            $table->text('diagnosis')->nullable(); 
            $table->tinyInteger('priority')->default(3); 
            $table->boolean('active')->default(true); 
            $table->timestamps();

            // Relación con documentos de identificación
            // Nombre y apellido del estudiante
            // Fecha de nacimiento para cálculo de edad
            // Relación con cursos (puede ser nulo si está en transición)
            // Estado activo y necesidad educativa especial


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
