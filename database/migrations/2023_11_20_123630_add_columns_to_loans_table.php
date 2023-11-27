<?php

use App\Library\Enums\LoanStatus;
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
        Schema::table('loans', function (Blueprint $table) {
            $table->unsignedBigInteger("loan_officer_id")->nullable()->after("id");
            $table->string("loan_officer_name", 200)->nullable()->after("loan_officer_id");
            $table->unsignedBigInteger("borrower_id")->nullable()->after("loan_officer_name");
            $table->string("borrower_name", 200)->nullable()->after("borrower_id");
            $table->unsignedBigInteger("loan_product_id")->nullable()->after("borrower_name");
            $table->string("loan_product_name", 100)->nullable()->after("loan_product_id");
            $table->decimal("interest_rate", 5, 2)->nullable()->after("loan_product_name");
            $table->unsignedBigInteger("organisation_id")->nullable()->after("interest_rate");
            $table->string("organisation_name", 200)->nullable()->after("organisation_id");
            $table->integer("number_of_installments")->nullable()->after("organisation_name");
            $table->decimal("principal", 15, 2)->nullable()->after("number_of_installments");
            $table->decimal("interest", 15, 2)->nullable()->after("principal");
            $table->decimal("charges", 15, 2)->nullable()->after("interest");
            $table->decimal("total_charges", 15, 2)->nullable()->after("charges");
            $table->decimal("total_loan", 15, 2)->nullable()->after("total_charges");
            $table->string("status")->nullable()->after("total_loan")->default(LoanStatus::Active->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn("loan_officer_id");
            $table->dropColumn("loan_officer_name");
            $table->dropColumn("borrower_id");
            $table->dropColumn("borrower_name");
            $table->dropColumn("loan_product_id");
            $table->dropColumn("loan_product_name");
            $table->dropColumn("interest_rate");
            $table->dropColumn("organisation_id");
            $table->dropColumn("organisation_name");
            $table->dropColumn("number_of_installments");
            $table->dropColumn("principal");
            $table->dropColumn("interest");
            $table->dropColumn("charges");
            $table->dropColumn("total_charges");
            $table->dropColumn("total_loan");
            $table->dropColumn("status");
        });
    }
};
