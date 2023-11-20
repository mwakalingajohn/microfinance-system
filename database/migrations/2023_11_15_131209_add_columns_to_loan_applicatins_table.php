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
            $table->unsignedBigInteger("borrower_id")->nullable()->after("id");
            $table->string('borrower_name', 100)->nullable()->after("borrower_id");
            $table->unsignedBigInteger("loan_product_id")->nullable()->after("borrower_name");
            $table->string('loan_product_name', 100)->nullable()->after("loan_product_id");
            $table->json('loan_product_details')->nullable()->after("loan_product_name");
            $table->decimal('amount', 15, 2)->nullable()->after("loan_product_name");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_applicatins', function (Blueprint $table) {
            $table->dropColumn("borrower_id");
            $table->dropColumn("borrower_name");
            $table->dropColumn("loan_product_id");
            $table->dropColumn("loan_product_name");
            $table->dropColumn("loan_product_details");
            $table->dropColumn("amount");
        });
    }
};
