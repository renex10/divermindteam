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
 Schema::create('documents', function (Blueprint $table) {
        $table->id();

        // Relación polimórfica: puede ser estudiante, profesional, sesión, etc.
        $table->morphs('documentable');

        $table->string('type');        // ej: medical_report, plan, consent_form
        $table->string('path');        // ej: storage path
        $table->string('format')->nullable();  // ej: pdf, jpg, docx
        $table->text('description')->nullable();

        $table->timestamps();
    });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
