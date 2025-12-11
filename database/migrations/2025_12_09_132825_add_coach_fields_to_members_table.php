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
            $table->foreignId('coach_id')->nullable()->after('gender')->constrained('coaches')->nullOnDelete();
            $table->enum('pt_billing_type', ['per_session', 'per_month'])->nullable()->after('coach_id');
            $table->decimal('pt_rate', 10, 2)->nullable()->after('pt_billing_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['coach_id']);
            $table->dropColumn([
                'coach_id',
                'pt_billing_type',
                'pt_rate',
            ]);
        });
    }
};
