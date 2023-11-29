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
        Schema::create('loan_repayment_details', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger("loan_officer_id")->nullable();
            $table->string("loan_officer_name", 200)->nullable();
            $table->unsignedBigInteger("borrower_id")->nullable();
            $table->string("borrower_name", 200)->nullable();
            $table->unsignedBigInteger("loan_product_id")->nullable();
            $table->string("loan_product_name", 100)->nullable();
            $table->unsignedBigInteger("organisation_id")->nullable();
            $table->string("organisation_name", 200)->nullable();
            $table->unsignedBigInteger("loan_id")->nullable();
            $table->unsignedBigInteger("loan_installment_id")->nullable();
            $table->unsignedBigInteger("loan_repayment_id")->nullable();
            $table->string('type', 100)->nullable();
            $table->dateTime("repayment_date")->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_repayment_details');
    }
};
