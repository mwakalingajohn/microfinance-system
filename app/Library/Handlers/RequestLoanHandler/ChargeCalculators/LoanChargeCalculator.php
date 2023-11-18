<?php

namespace App\Library\Handlers\RequestLoanHandler\ChargeCalculators;

use App\Charge;
use App\LoanProduct;

class LoanChargeCalculator
{

    public function __construct(
        private array $installments,
        private LoanProduct $loanProduct)
    {
    }

    public function run()
    {
        $charges = $this->loanProduct->charges;
        $installments = $this->installments;

        foreach ($charges as $key => $charge) {
            $instance = $this->getChargeCalculationInstance(
                $charge
            );

            if($instance){
                $installments = $instance->handle($installments, $charge->toArray());
            }
        }

        return $installments;
    }

    public function getChargeCalculationInstance($charge): ChargeCalculator | null
    {
        $chargeCalculationInstance = null;
        switch ($charge["from"]) {
            case Charge::FROM['first_installment']:
                $chargeCalculationInstance = new FirstInstallmentChargeCalculator();
                break;
            case Charge::FROM['principal']:
                $chargeCalculationInstance = new PrincipleChargeCalculator();
                break;
            case Charge::FROM['last_installment']:
                $chargeCalculationInstance = new LastInstallmentChargeCalculator();
                break;
            default:
                break;
        }
        return $chargeCalculationInstance;
    }
}
