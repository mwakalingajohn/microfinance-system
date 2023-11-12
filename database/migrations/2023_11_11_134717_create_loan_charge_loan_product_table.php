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
        Schema::create('loan_charge_loan_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("loan_charge_id");
            $table->unsignedBigInteger("loan_product_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_charge_loan_product');
    }
};
