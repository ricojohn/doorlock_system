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
        Schema::table('member_subscriptions', function (Blueprint $table) {
            $table->date('frozen_at')->nullable()->after('notes');
            $table->date('frozen_until')->nullable()->after('frozen_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['frozen_at', 'frozen_until']);
        });
    }
};
