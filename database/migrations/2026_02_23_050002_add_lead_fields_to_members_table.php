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
        Schema::table('members', function (Blueprint $table) {
            $table->foreignId('guest_id')->nullable()->after('id')->constrained('guests')->nullOnDelete();
            $table->string('invited_by_type')->nullable()->after('guest_id');
            $table->unsignedBigInteger('invited_by_id')->nullable()->after('invited_by_type');
            $table->foreignId('converted_by_user_id')->nullable()->after('invited_by_id')->constrained('users')->nullOnDelete();

            $table->index(['invited_by_type', 'invited_by_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['guest_id']);
            $table->dropForeign(['converted_by_user_id']);
            $table->dropColumn(['guest_id', 'invited_by_type', 'invited_by_id', 'converted_by_user_id']);
        });
    }
};
