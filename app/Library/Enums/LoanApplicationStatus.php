<?php

namespace App\Library\Enums;

enum LoanApplicationStatus: string implements ShouldReturnValues
{
    use ReturnsValues;

    case Failed = "failed";
    case Created = "created";
    case PendingApprovals = "pending_approvals";
    case PendingDisbursement = "pending_disbursement";
    case Validating = "validating";
    case CalculatingLoan = "calculating_loan";
    case SavingLoanDetails = "saving_loan_details";
    case DisbursingToUser = "disbursing_to_user";
    case Disbursed = "disbursed";

}
