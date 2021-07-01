<?php

namespace Database\Factories;

use App\Models\App;
use App\Models\Device;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'device_id' => Device::factory()->create()->id,
            'app_id' => App::factory()->create()->id,
            'receipt' => $this->faker->randomNumber(8, true),
            'status' => $this->faker->randomElement(['pending', 'active', 'canceled']),
            'expires_at' => $this->faker->dateTimeThisYear('now', 'UTC'),
        ];
    }
}
