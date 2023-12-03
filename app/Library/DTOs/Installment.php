<?php

namespace App\Library\DTOs;

class Installment
{
    public function __construct(
        public mixed $loanBalance = 0,
        public mixed $principal = 0,
        public mixed $interest = 0,
        public mixed $installment = 0,
        public mixed $dueDate = null,
        public mixed $charges = 0,
        public array $installmentCharges = []
    ) {
    }

    public function toArray()
    {
        return [
            "loanBalance" => $this->loanBalance,
            "principal" => $this->principal,
            "interest" => $this->interest,
            "installment" => $this->installment,
            "due_date" => $this->dueDate??null,
            "charges" => $this->charges,
            "installmentCharges" => $this->installmentCharges
        ];
    }
}
