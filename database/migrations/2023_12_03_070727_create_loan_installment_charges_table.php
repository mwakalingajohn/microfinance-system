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
        Schema::create('loan_installment_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_installment_id')->nullable();
            $table->unsignedBigInteger('charge_id')->nullable();
            $table->unsignedBigInteger('loan_officer_id')->nullable();
            $table->unsignedBigInteger('organisation_id')->nullable();
            $table->unsignedBigInteger('borrower_id')->nullable();
            $table->unsignedBigInteger('loan_product_id')->nullable();
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->string('label', 100)->nullable();
            $table->string('on', 100)->nullable();
            $table->string('type', 100)->nullable();
            $table->string('of', 100)->nullable();
            $table->string('value', 100)->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('remaining_amount', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_installment_charges');
    }
};
