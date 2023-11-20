<?php

use App\Library\Enums\Gender;
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
        Schema::table('borrowers', function (Blueprint $table) {
            $table->unsignedBigInteger("user_id")->nullable()->after("id");
            $table->unsignedBigInteger("organization_id")->nullable()->after("user_id");
            $table->string('first_name', 100)->nullable()->after("organization_id");
            $table->string('middle_name', 100)->nullable()->after("first_name");
            $table->string('last_name', 100)->nullable()->after("middle_name");
            $table->string('email', 100)->nullable()->after("last_name");
            $table->string('sex', 100)->nullable()->after("email");
            $table->unsignedBigInteger("street_id")->nullable()->after("sex");
            $table->date('birthdate')->nullable()->after("street_id");
            $table->string('occupation', 100)->nullable()->after("birthdate");
            $table->string('marital_status', 100)->nullable()->after("occupation");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowers', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('organization_id');
            $table->dropColumn('first_name');
            $table->dropColumn('middle_name');
            $table->dropColumn('last_name');
            $table->dropColumn('email');
            $table->dropColumn('sex');
            $table->dropColumn('street_id');
            $table->dropColumn('birthdate');
            $table->dropColumn('occupation');
            $table->dropColumn('marital_status');
        });
    }
};
