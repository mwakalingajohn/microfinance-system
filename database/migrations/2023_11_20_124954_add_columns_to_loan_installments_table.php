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
        Schema::table('loan_installments', function (Blueprint $table) {
            $table->unsignedBigInteger("loan_id")->nullable()->after("id");
            $table->unsignedBigInteger("loan_officer_id")->nullable()->after("loan_id");
            $table->string("loan_officer_name", 200)->nullable()->after("loan_officer_id");
            $table->unsignedBigInteger("borrower_id")->nullable()->after("loan_officer_name");
            $table->string("borrower_name", 200)->nullable()->after("borrower_id");
            $table->unsignedBigInteger("loan_product_id")->nullable()->after("borrower_name");
            $table->string("loan_product_name", 100)->nullable()->after("loan_product_id");
            $table->decimal("interest_rate", 5, 2)->nullable()->after("loan_product_name");
            $table->unsignedBigInteger("organisation_id")->nullable()->after("interest_rate");
            $table->string("organisation_name", 200)->nullable()->after("organisation_id");
            $table->decimal("loan_balance", 15, 2)->nullable()->after("organisation_name");
            $table->decimal("principal", 15, 2)->nullable()->after("loan_balance");
            $table->decimal("interest", 15, 2)->nullable()->after("principal");
            $table->decimal("charges", 15, 2)->nullable()->after("interest");
            $table->decimal("installment", 15, 2)->nullable()->after("charges");
            $table->decimal("penalty", 15, 2)->nullable()->after("installment");
            $table->decimal("remaining_principal", 15, 2)->nullable()->after("penalty");
            $table->decimal("remaining_interest", 15, 2)->nullable()->after("remaining_principal");
            $table->decimal("remaining_charges", 15, 2)->nullable()->after("remaining_interest");
            $table->decimal("remaining_penalty", 15, 2)->nullable()->after("remaining_charges");
            $table->decimal("remaining_installment", 15, 2)->nullable()->after("remaining_penalty");
            $table->dateTime("due_date")->nullable()->after("remaining_installment");
            $table->string("status", 100)->nullable()->after("due_date");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_installments', function (Blueprint $table) {
            $table->dropColumn("loan_id");
            $table->dropColumn("loan_officer_id");
            $table->dropColumn("loan_officer_name");
            $table->dropColumn("borrower_id");
            $table->dropColumn("borrower_name");
            $table->dropColumn("loan_product_id");
            $table->dropColumn("loan_product_name");
            $table->dropColumn("interest_rate");
            $table->dropColumn("organisation_id");
            $table->dropColumn("organisation_name");
            $table->dropColumn("loan_balance");
            $table->dropColumn("principal");
            $table->dropColumn("interest");
            $table->dropColumn("charges");
            $table->dropColumn("installment");
            $table->dropColumn("penalty");
            $table->dropColumn("remaining_principal");
            $table->dropColumn("remaining_interest");
            $table->dropColumn("remaining_charges");
            $table->dropColumn("remaining_penalty");
            $table->dropColumn("remaining_installment");
            $table->dropColumn("due_date");
            $table->dropColumn('status');
        });
    }
};
