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
        Schema::create('access_logs', function (Blueprint $table) {
            $table->id();
            $table->string('card_number');
            $table->foreignId('rfid_card_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete();
            $table->string('member_name')->nullable();
            $table->enum('access_granted', ['granted', 'denied'])->default('denied');
            $table->string('reason')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamp('accessed_at');
            $table->timestamps();

            $table->index('card_number');
            $table->index('accessed_at');
            $table->index('access_granted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_logs');
    }
};

