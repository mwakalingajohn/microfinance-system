<?php

namespace Database\Factories;

use App\Library\Enums\DueDateMethod;
use App\Library\Enums\InterestPeriod;
use App\Library\Enums\LoanCalculationMethod;
use App\Library\Enums\RepaymentOrderItem;
use App\Models\LoanProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoanProduct>
 */
class LoanProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => $this->faker->word,
            'minimum_principal' => $this->faker->randomFloat(2, 100, 1000),
            'maximum_principal' => $this->faker->randomFloat(2, 1000, 100000),
            'default_interest_rate' => $this->faker->randomFloat(2, 1, 10),
            'minimum_interest_rate' => $this->faker->randomFloat(2, 1, 10),
            'maximum_interest_rate' => $this->faker->randomFloat(2, 1, 10),
            'interest_period' => $this->faker->randomElement(InterestPeriod::values()),
            'repayment_period' => $this->faker->randomElement(InterestPeriod::values()),
            'calculation_method' => $this->faker->randomElement(LoanCalculationMethod::values()),
            'due_date_method' => $this->faker->randomElement(DueDateMethod::values()),
            'grace_on_interest' => $this->faker->randomDigitNotZero(),
            'repayment_order' => RepaymentOrderItem::values(),
        ];
    }
}
