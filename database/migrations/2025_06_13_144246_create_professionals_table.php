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
        Schema::create('professionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade')->comment('1:1 relation with users table');
            $table->foreignId('specialty_id')->constrained('specialties')->onDelete('restrict')->comment('Primary specialty');
            $table->boolean('active')->default(true)->comment('Is the professional active in the system?');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professionals');
    }
};
