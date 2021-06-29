<?php

namespace Database\Factories;

use App\Models\OS;
use Illuminate\Database\Eloquent\Factories\Factory;

class OSFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OS::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->randomElement([1, 2]),
            'name' => $this->faker->unique()->randomElement(['iOS', 'Android'])
        ];
    }
}
