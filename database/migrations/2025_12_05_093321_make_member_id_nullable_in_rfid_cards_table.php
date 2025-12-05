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
        Schema::table('rfid_cards', function (Blueprint $table) {
            $table->dropForeign('rfid_cards_member_id_foreign');
        });

        Schema::table('rfid_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('member_id')->nullable()->change();
            $table->foreign('member_id')
                ->references('id')
                ->on('members')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rfid_cards', function (Blueprint $table) {
            $table->dropForeign('rfid_cards_member_id_foreign');
        });

        Schema::table('rfid_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('member_id')->nullable(false)->change();
            $table->foreign('member_id')
                ->references('id')
                ->on('members')
                ->cascadeOnDelete();
        });
    }
};
