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
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->integer('number_of_installments')->nullable()->after("amount");
            $table->unsignedBigInteger("loan_officer_id")->nullable()->after("borrower_id");
            $table->unsignedBigInteger("branch_id")->nullable()->after("borrower_id");
            $table->decimal("interest")->nullable()->after("number_of_installments");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropColumn("number_of_installments");
            $table->dropColumn("loan_officer_id");
            $table->dropColumn("branch_id");
            $table->dropColumn("interest");
        });
    }
};
