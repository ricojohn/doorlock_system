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
        Schema::create('pt_session_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pt_session_plan_id')->constrained('pt_session_plans')->cascadeOnDelete();
            $table->string('exercise_name');
            $table->integer('sets')->nullable();
            $table->integer('reps')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('rest_period_seconds')->nullable();
            $table->text('notes')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pt_session_plan_items');
    }
};
