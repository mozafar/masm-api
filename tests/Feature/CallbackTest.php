<?php

namespace Tests\Feature;

use App\Models\App;
use App\Models\Subscription;
use App\Services\Callback\Notifications\StatusChanged;
use App\Services\Callback\Worker\ProcessSubscriptions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CallbackTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        
        Notification::fake();
    }

    /** @test */
    public function it_should_send_callback_notification()
    {
        $subscription = Subscription::factory()->create();
        $app = $subscription->app()->first(); 
        Notification::assertSentTo(
            [$app], StatusChanged::class
        );
    }
}
