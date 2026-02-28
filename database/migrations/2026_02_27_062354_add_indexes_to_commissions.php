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
        Schema::table('commissions', function (Blueprint $table) {
            $table->index('member_id');
            $table->index('coach_id');
            $table->index('pt_package_id');
            $table->index('member_pt_package_id');
            $table->index('pt_session_id');
            $table->index('status');
            $table->index('earned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            $table->dropIndex(['member_id']);
            $table->dropIndex(['coach_id']);
            $table->dropIndex(['pt_package_id']);
            $table->dropIndex(['member_pt_package_id']);
            $table->dropIndex(['pt_session_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['earned_at']);
        });
    }
};
