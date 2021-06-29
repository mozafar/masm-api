<?php

namespace Database\Factories;

use App\Models\App;
use App\Models\Device;
use App\Models\OS;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DeviceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Device::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $os = (OS::count() < 2) ? OS::factory()->create() : OS::all()->random();
        return [
            'u_id' => $this->faker->randomNumber(null, false),
            'os_id' => $os->id,
            'language' => $this->faker->randomElement(['EN', 'FA', 'AR', 'TR'])
        ];
    }
}
