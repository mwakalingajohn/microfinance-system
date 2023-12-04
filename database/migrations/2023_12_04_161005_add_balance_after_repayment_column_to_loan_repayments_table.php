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
        Schema::table('loan_repayments', function (Blueprint $table) {
            $table->decimal('balance_before_repayment', 15, 2)->nullable()->after("amount");
            $table->decimal('balance_after_repayment', 15, 2)->nullable()->after("balance_before_repayment");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_repayments', function (Blueprint $table) {
            $table->dropColumn('balance_after_repayment');
            $table->dropColumn('balance_before_repayment');
        });

    }
};
