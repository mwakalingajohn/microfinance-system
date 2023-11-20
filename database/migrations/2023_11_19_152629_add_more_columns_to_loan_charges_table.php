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
        Schema::table('loan_charges', function (Blueprint $table) {
            $table->string("of")->after("from")->nullable();
            $table->renameColumn('from', 'on');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_charges', function (Blueprint $table) {
            $table->dropColumn('of');
            $table->renameColumn('on', 'from');
        });
    }
};
