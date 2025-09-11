<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Rezervacija;
use App\Models\Soba;
use App\Models\Status;
use App\Models\User;

class RezervacijaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rezervacija::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'datum_od' => fake()->date(),
            'datum_do' => fake()->date(),
            'broj_osoba' => fake()->numberBetween(-10000, 10000),
            'napomena' => fake()->text(),
            'user_id' => User::factory(),
            'soba_id' => Soba::factory(),
            'status_id' => Status::factory(),
        ];
    }
}
