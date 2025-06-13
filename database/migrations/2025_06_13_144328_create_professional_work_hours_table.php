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
        Schema::create('professional_work_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id')->constrained()->onDelete('cascade');
            $table->integer('weekly_hours')->comment('Total weekly contracted hours');
            $table->json('availability')->nullable()->comment('Days and hours available per week');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professional_work_hours');
    }
};
