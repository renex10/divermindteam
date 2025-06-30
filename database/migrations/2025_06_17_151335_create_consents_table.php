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
        // Consentimientos
        Schema::create('consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guardian_student_id')->constrained('guardian_students')->onDelete('cascade');
            $table->foreignId('establishment_id') // Nuevo campo
                ->constrained('establishments')
                ->onDelete('cascade');
            $table->boolean('consent_pie')->default(false);
            $table->boolean('data_processing')->default(false);
            $table->date('signed_at')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consents');
    }
};
