<?php

namespace Tests\Feature;

use App\Models\App;
use App\Models\OS;
use App\Models\Subscription;
use App\Services\Callback\Notifications\StatusChanged;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class APITest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    /** @test */
    public function it_can_register()
    {
        $os = (OS::count() < 2) ? OS::factory()->create() : OS::all()->random();
        $response = $this->postJson('/api/register', [
            'u_id' => 1,
            'app_id' => App::factory()->create()->id,
            'os_id' => $os->id,
            'language' => 'EN',
        ]);
        $response->assertOk();
        $this->assertTrue(! empty($response['token']));
    }

    /** @test */
    public function it_should_get_different_tokens_for_multiple_registrations()
    {
        $os = (OS::count() < 2) ? OS::factory()->create() : OS::all()->random();
        $response1 = $this->postJson('/api/register', [
            'u_id' => 1,
            'app_id' => App::factory()->create()->id,
            'os_id' => $os->id,
            'language' => 'EN',
        ]);

        $response2 = $this->postJson('/api/register', [
            'u_id' => 1,
            'app_id' => App::factory()->create()->id,
            'os_id' => $os->id,
            'language' => 'EN',
        ]);

        $response1->assertOk();
        $response2->assertOk();
        $this->assertTrue(! empty($response1['token']));
        $this->assertTrue(! empty($response2['token']));
        $this->assertNotEquals($response1['token'], $response2['token']);
    }

    /** @test */
    public function it_can_check_subscription()
    {
        $subscription = Subscription::factory()->create([
            'receipt' => '123',
        ]);
        $token = $subscription->createToken();
        $response = $this->getJson("/api/check-subscription?token=$token");

        $app = $subscription->app()->first();
        Notification::assertSentTo(
            [$app], StatusChanged::class
        );
        $response->assertOk();
        $this->assertTrue(! empty($response['status']));
    }

    /** @test */
    public function it_can_purchase_subscription()
    {
        $subscription = Subscription::factory()->create();
        $token = $subscription->createToken();

        $response = $this->postJson('/api/purchase', [
            'token' => $token,
            'receipt' => '12345',
        ]);

        $app = $subscription->app()->first();
        Notification::assertSentTo(
            [$app], StatusChanged::class
        );

        $response->assertOk();
    }

    /** @test */
    public function it_should_report_based_on_app_day_os()
    {
        $app = App::factory()
            ->hasSubscriptions(100)
            ->create();

        $date = now()->format('Y-m-d');
        $expected = $app->subscriptions()
            ->select('status', DB::raw('count(*) as count'))
            ->whereDate('updated_at', $date)
            ->groupBy('status')
            ->get()
            ->toArray();

        $response = $this->getJson("/api/report/apps/{$app->id}?date=$date");

        $response->assertOk()->assertJson($expected);
    }
}
