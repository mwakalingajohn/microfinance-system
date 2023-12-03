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
            $table->unsignedBigInteger('loan_officer_id')->nullable()->after("id");
            $table->unsignedBigInteger('organisation_id')->nullable()->after("loan_officer_id");
            $table->unsignedBigInteger('borrower_id')->nullable()->after("organisation_id");
            $table->unsignedBigInteger('loan_product_id')->nullable()->after("borrower_id");
            $table->unsignedBigInteger('loan_id')->nullable()->after("loan_product_id");
            $table->decimal('amount', 15, 2)->nullable()->after("loan_id");
            $table->dateTime('repayment_date')->nullable()->after("amount");
            $table->string('proof_of_payment', 512)->nullable()->after("repayment_date");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_repayments', function (Blueprint $table) {
            $table->dropColumn('loan_officer_id');
            $table->dropColumn('organisation_id');
            $table->dropColumn('borrower_id');
            $table->dropColumn('loan_product_id');
            $table->dropColumn('loan_id');
            $table->dropColumn('amount');
            $table->dropColumn('repayment_date');
            $table->dropColumn('proof_of_payment');
        });
    }
};
