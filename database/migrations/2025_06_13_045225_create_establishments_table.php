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
            // Establecimientos
            Schema::create('establishments', function (Blueprint $table) {
                  $table->id();

                  // RBD oficial del establecimiento
                  $table->unsignedInteger('rbd')
                        ->unique()
                        ->comment('RBD oficial asignado por el Ministerio de Educación');

                  $table->string('name', 100)
                        ->comment('Official name of the educational establishment');

                  $table->string('address', 200)
                        ->comment('Full physical address');

                  $table->foreignId('commune_id')
                        ->constrained()
                        ->onDelete('restrict')
                        ->onUpdate('cascade')
                        ->comment('Commune where the establishment is located');

                  $table->integer('pie_quota_max')
                        ->default(0)
                        ->comment('Maximum number of students who can join the PIE program');

                  $table->boolean('is_active')
                        ->default(true)
                        ->comment('Indicates if the establishment is active (true) or inactive (false)');

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
