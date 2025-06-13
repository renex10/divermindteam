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
        Schema::create('establishments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('Official name of the educational establishment');
            $table->string('address', 200)->comment('Full physical address');
            $table->foreignId('commune_id')->constrained()->onDelete('restrict')->onUpdate('cascade')->comment('Commune where the establishment is located');
            $table->integer('pie_quota_max')->default(0)->comment('Maximum number of students who can join the PIE program');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establishments');
    }
};
