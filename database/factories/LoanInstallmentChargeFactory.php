<?php

namespace Database\Factories;

use App\Library\Enums\DeductibleValueType;
use App\Library\Enums\LoanChargeDestination;
use App\Library\Enums\LoanChargeSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoanInstallmentCharge>
 */
class LoanInstallmentChargeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = $this->faker->randomFloat(min: 100, max: 1000);
        return [
            "loan_installment_id" => $this->faker->randomNumber(),
            "charge_id" => $this->faker->randomNumber(),
            "loan_officer_id" => $this->faker->randomNumber(),
            "organisation_id" => $this->faker->randomNumber(),
            "borrower_id" => $this->faker->randomNumber(),
            "loan_product_id" => $this->faker->randomNumber(),
            "loan_id" => $this->faker->randomNumber(),
            "label" =>  $this->faker->word,
            "on" => $this->faker->randomElement(LoanChargeDestination::values()),
            "type" => $this->faker->randomElement(DeductibleValueType::values()),
            "of" => $this->faker->randomElement(LoanChargeSource::values()),
            "value" => $this->faker->randomFloat(min: 100, max: 1000),
            "amount" => $amount,
            "remaining_amount" => $amount
        ];
    }
}
