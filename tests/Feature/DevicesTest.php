<?php

namespace Tests\Feature;

use App\Models\App;
use App\Models\OS;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DevicesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_device_can_register()
    {
        $os = (OS::count() < 2) ? OS::factory()->create() : OS::all()->random();
        $response = $this->postJson('/api/register', [
            'u_id' => 1,
            'app_id' => App::factory()->create()->id,
            'os_id' => $os->id,
            'language' => 'EN'
        ]);
        $response->assertOk();
        $this->assertTrue(!empty($response['token']));
    }

    /** @test */
    public function when_device_registers_multiple_times_should_get_different_tokens()
    {
        $os = (OS::count() < 2) ? OS::factory()->create() : OS::all()->random();
        $response1 = $this->postJson('/api/register', [
            'u_id' => 1,
            'app_id' => App::factory()->create()->id,
            'os_id' => $os->id,
            'language' => 'EN'
        ]);

        $response2 = $this->postJson('/api/register', [
            'u_id' => 1,
            'app_id' => App::factory()->create()->id,
            'os_id' => $os->id,
            'language' => 'EN'
        ]);

        $response1->assertOk();
        $response2->assertOk();
        $this->assertTrue(!empty($response1['token']));
        $this->assertTrue(!empty($response2['token']));
        $this->assertNotEquals($response1['token'], $response2['token']);
    }

    /** @test */
    public function a_device_can_check_subscription()
    {
        $token = Subscription::factory()->make()->createToken();
        $response = $this->getJson("/api/check-subscription?token=$token");

        $response->assertOk();
        $this->assertTrue(!empty($response['status']));
    }

    /** @test */
    public function a_device_can_purchase_subscription()
    {
        $token = Subscription::factory()->make()->createToken();
        $response = $this->postJson('/api/purchase', [
            'token' => $token,
            'receipt' => '12345'
        ]);

        $response->assertOk();
    }
}
