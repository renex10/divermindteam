<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id(); // Asegurar BIGINT UNSIGNED con AUTO_INCREMENT

            // Relación con usuarios (no almacena datos personales duplicados)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');

            // Relación con documentos de identificación
            $table->foreignId('document_id')->nullable()->constrained('user_documents')->onDelete('set null')->onUpdate('cascade');

            // Relación con cursos
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('set null')->onUpdate('cascade');

            // Datos exclusivos del estudiante (sin redundancia)
            $table->date('birth_date');
            $table->enum('need_type', ['permanent', 'temporary'])->default('temporary');
            $table->text('diagnosis')->nullable();
            $table->tinyInteger('priority')->default(3)->comment('1: Máxima, 2: Media, 3: Básica');
            $table->boolean('active')->default(true);

            $table->timestamps();

            // Índices para mejorar la performance en consultas frecuentes
            $table->index('priority');
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};