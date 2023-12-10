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
        Schema::table('loan_penalties', function (Blueprint $table) {
            $table->unsignedBigInteger('loan_id')->nullable()->after("id");
            $table->decimal("amount", 15, 2)->nullable()->after("value");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_penalties', function (Blueprint $table) {
            $table->dropColumn("loan_id");
            $table->dropColumn("amount");
        });
    }
};
