<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Services\Worker\AddSubscriptionsJob;
use App\Services\Worker\CheckSubscriptionJob;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class WorkerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_should_dispatch_add_subscription_job()
    {
        Queue::fake();

        AddSubscriptionsJob::dispatch();
        
        Queue::assertPushed(AddSubscriptionsJob::class);
    }

    /** @test */
    public function it_should_dispatch_check_subscription_job()
    {
        Bus::fake([
            CheckSubscriptionJob::class
        ]);
        
        Subscription::factory()->count(100)->create();
        AddSubscriptionsJob::dispatch();

        Bus::assertDispatched(CheckSubscriptionJob::class);
    }
}
