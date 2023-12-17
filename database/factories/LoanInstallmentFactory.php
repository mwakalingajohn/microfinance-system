<?php

namespace Database\Factories;

use App\Library\Enums\LoanInstallmentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoanInstallment>
 */
class LoanInstallmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $principal = $this->faker->randomFloat();
        $interest = $this->faker->randomFloat();
        $charges = $this->faker->randomFloat();
        $installment = $this->faker->randomFloat();
        $penalty = $this->faker->randomFloat();

        return [
            "loan_id" => $this->faker->randomNumber(),
            "loan_officer_id" => $this->faker->randomNumber(),
            "loan_officer_name" => $this->faker->name(),
            "borrower_id" => $this->faker->randomNumber(),
            "borrower_name" => $this->faker->name(),
            "loan_product_id" => $this->faker->randomNumber(),
            "loan_product_name" => $this->faker->name(),
            "interest_rate" => $this->faker->randomFloat(),
            "organisation_id" => $this->faker->randomNumber(),
            "organisation_name" => $this->faker->name(),
            "loan_balance" => $this->faker->randomFloat(),
            "principal" => $principal,
            "interest" => $interest,
            "charges" => $charges,
            "installment" => $installment,
            "penalty" => $penalty,
            "remaining_principal" => $principal,
            "remaining_interest" => $interest,
            "remaining_charges" => $charges,
            "remaining_penalty" => $penalty,
            "remaining_installment" => $installment,
            "due_date" => $this->faker->dateTime(),
            "status" => $this->faker->randomElement(LoanInstallmentStatus::values()),
        ];
    }
}
