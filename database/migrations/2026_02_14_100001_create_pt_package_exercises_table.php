<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pt_package_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pt_package_id')->constrained('pt_packages')->cascadeOnDelete();
            $table->string('exercise_name');
            $table->integer('sets')->nullable();
            $table->integer('reps')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('rest_period_seconds')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pt_package_exercises');
    }
};
