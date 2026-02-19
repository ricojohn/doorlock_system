<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('member_pt_packages', function (Blueprint $table) {
            $table->string('receipt_number')->nullable()->after('payment_type');
            $table->string('receipt_image')->nullable()->after('receipt_number');
        });
    }

    public function down(): void
    {
        Schema::table('member_pt_packages', function (Blueprint $table) {
            $table->dropColumn(['receipt_number', 'receipt_image']);
        });
    }
};
