<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pt_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('package_rate', 10, 2);
            $table->unsignedInteger('session_count');
            $table->decimal('rate_per_session', 10, 2)->nullable();
            $table->decimal('commission_percentage', 5, 2)->nullable();
            $table->decimal('commission_per_session', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->enum('package_type', ['New', 'Renewal']);
            $table->foreignId('coach_id')->nullable()->constrained('coaches')->nullOnDelete();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('payment_type')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pt_packages');
    }
};
