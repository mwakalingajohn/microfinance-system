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
        Schema::create('loan_products', function (Blueprint $table) {
            $table->id();
            $table->string('label', 100)->nullable();
            $table->decimal('minimum_principal', 15, 2)->nullable();
            $table->decimal('maximum_principal', 15, 2)->nullable();
            $table->decimal('default_interest_rate', 15, 2)->nullable();
            $table->decimal('minimum_interest_rate', 5, 2)->nullable();
            $table->decimal('maximum_interest_rate', 5, 2)->nullable();
            $table->string('interest_period', 100)->nullable();
            $table->string("repayment_period", 100)->nullable();
            $table->string("calculation_method", 100)->nullable();
            $table->string("due_date_method", 100)->nullable();
            $table->integer("grace_on_interest")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};
