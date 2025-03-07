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
            $table->string('status', 100)->nullable()->after("loan_product_details");
            $table->text('comment')->nullable()->after("status");
            $table->unsignedBigInteger("loan_id")->nullable()->after("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('comment');
            $table->dropColumn('loan_id');
        });
    }
};
