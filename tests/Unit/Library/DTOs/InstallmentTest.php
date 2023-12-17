<?php

namespace Library\DTOs;

use App\Library\DTOs\Installment;
use PHPUnit\Framework\TestCase;

class InstallmentTest extends TestCase
{
    public function testToArray()
    {
        $installment = new Installment(
            loanBalance: 1000,
            principal: 100,
            interest: 10,
            installment: 110,
            dueDate: "2021-01-01",
            charges: 10,
            installmentCharges: [
                [
                    "id" => 1,
                    "name" => "Charge 1",
                    "amount" => 10,
                ],
                [
                    "id" => 2,
                    "name" => "Charge 2",
                    "amount" => 20,
                ],
            ]
        );

        $expected = [
            "loanBalance" => 1000,
            "principal" => 100,
            "interest" => 10,
            "installment" => 110,
            "due_date" => "2021-01-01",
            "charges" => 10,
            "installmentCharges" => [
                [
                    "id" => 1,
                    "name" => "Charge 1",
                    "amount" => 10,
                ],
                [
                    "id" => 2,
                    "name" => "Charge 2",
                    "amount" => 20,
                ],
            ],
        ];

        $this->assertEquals($expected, $installment->toArray());
    }

    public function testToArrayWithoutCharges()
    {
        $installment = new Installment(
            loanBalance: 1000,
            principal: 100,
            interest: 10,
            installment: 110,
            dueDate: "2021-01-01",
            charges: 10,
            installmentCharges: [
                [
                    "id" => 1,
                    "name" => "Charge 1",
                    "amount" => 10,
                ],
                [
                    "id" => 2,
                    "name" => "Charge 2",
                    "amount" => 20,
                ],
            ]
        );

        $expected = [
            "loanBalance" => 1000,
            "principal" => 100,
            "interest" => 10,
            "installment" => 110,
            "due_date" => "2021-01-01",
            "charges" => 10,
        ];

        $this->assertEquals($expected, $installment->toArray(false));
    }

}
