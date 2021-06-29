<?php

namespace App\Services\Worker;

use App\Models\Subscription;
use App\Services\Worker\Events\SubscriptionsProccessedEvent;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;

class AddSubscriptionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function handle()
    {
        if (App::runningUnitTests()) {
            $this->createTestBatch();
        }

        $this->createBatch();
    }

    private function createBatch()
    {
        $batch = Bus::batch([])
            ->allowFailures() 
            ->finally(function () {
                SubscriptionsProccessedEvent::dispatch();
            })->dispatch();

        Subscription::active()
            ->cursor()
            ->map(fn (Subscription $subscription) => $this->createCheckSubscriptionJob($subscription))
            ->filter()
            ->chunk(1000)
            ->each(function (LazyCollection $jobs) use ($batch) {
                $batch->add($jobs);
            }); 
    }

    private function createTestBatch()
    {
        $subscriptions = Subscription::active()
            ->get()
            ->map(fn (Subscription $subscription) => $this->createCheckSubscriptionJob($subscription))
            ->filter()
            ->toArray();

        $batch = Bus::batch($subscriptions)
            ->allowFailures() 
            ->finally(function () {
                SubscriptionsProccessedEvent::dispatch();
            })->dispatch(); 
    }


    private function createCheckSubscriptionJob(Subscription $subscription): CheckSubscriptionJob
    {
        return new CheckSubscriptionJob($subscription);
    }
}
