<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_pt_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('pt_package_id')->constrained('pt_packages')->cascadeOnDelete();
            $table->foreignId('coach_id')->constrained('coaches')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'expired', 'exhausted', 'cancelled'])->default('active');
            $table->string('payment_type')->nullable();
            $table->decimal('price_paid', 10, 2);
            $table->decimal('rate_per_session', 10, 2)->nullable();
            $table->decimal('commission_percentage', 5, 2)->nullable();
            $table->decimal('commission_per_session', 10, 2)->nullable();
            $table->unsignedInteger('sessions_total');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_pt_packages');
    }
};
