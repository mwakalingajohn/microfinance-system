<?php

use App\Library\Enums\LoanDisbursementMethod;
use App\Library\Enums\LoanDisbursementStatus;
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
        Schema::create('loan_disbursements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("loan_application_id");
            $table->unsignedBigInteger("loan_id");
            $table->unsignedBigInteger("disbursed_by");
            $table->string('method', 100)->default(LoanDisbursementMethod::Cash->value);
            $table->decimal('amount', 15, 2)->nullable();
            $table->dateTime('disbursed_on')->nullable()->useCurrent();
            $table->text('comment')->nullable();
            $table->string('status', 100)->default(LoanDisbursementStatus::Pending->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_disbursements');
    }
};
