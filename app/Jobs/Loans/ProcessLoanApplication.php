<?php

namespace App\Jobs\Loans;

use App\Library\Enums\LoanApplicationStatus;
use App\Library\Handlers\ProcessLoanApplication\Calculators\CalculateCharges;
use App\Library\Handlers\ProcessLoanApplication\Calculators\CalculateInstallments;
use App\Library\Handlers\ProcessLoanApplication\Calculators\LoanCalculation;
use App\Library\Handlers\ProcessLoanApplication\LoanApplicationHandler;
use App\Library\Handlers\ProcessLoanApplication\Validators\ItHasNotBeenProcessed;
use App\Library\Handlers\ProcessLoanApplication\Validators\ItIsFullyApproved;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Models\LoanDisbursement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Support\Facades\Pipeline;
use Throwable;

class ProcessLoanApplication implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 3600;

    /**
     * Create a new job instance.
     */
    public function __construct(
        #[WithoutRelations]
        public LoanApplication $loanApplication,
        public array $loanDisbursementData
    ) {
        //
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->loanApplication->id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new LoanApplicationHandler(
            $this->loanApplication,
            $this->loanDisbursementData
        ))->handle();
    }
}
