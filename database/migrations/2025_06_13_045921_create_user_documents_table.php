<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// Documentos de usuario
public function up(): void
{
    Schema::create('user_documents', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('rut', 12)->unique()->comment('Chilean RUT format 12345678-9');
        $table->boolean('verified')->default(false)->comment('Verification status of the document');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_documents');
    }
};
