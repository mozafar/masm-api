<?php

namespace Database\Factories;

use App\Models\App;
use App\Models\OS;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = App::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $os = (OS::count() < 2) ? OS::factory()->create() : OS::all()->random();
        return [
            'os_id' => $os->id,
            'username' => $this->faker->userName(),
            'password' => $this->faker->password()
        ];
    }

}
