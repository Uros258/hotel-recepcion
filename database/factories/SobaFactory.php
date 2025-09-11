<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Soba;

class SobaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Soba::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'broj_sobe' => fake()->numberBetween(-10000, 10000),
            'tip_sobe' => fake()->word(),
            'cena' => fake()->randomFloat(2, 0, 999999.99),
            'status_sobe' => fake()->word(),
        ];
    }
}
