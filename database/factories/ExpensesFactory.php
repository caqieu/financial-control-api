<?php

namespace Database\Factories;

use App\Models\Expenses;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expenses>
 */
class ExpensesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'descricao' => fake()->text(45),
            'valor' => fake()->numberBetween(0, 10000000),
            'data' => fake()->date(),
            'categoria' => array_rand(Expenses::CATEGORIES)
        ];
    }
}
