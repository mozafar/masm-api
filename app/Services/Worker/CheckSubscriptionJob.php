<?php

namespace App\Services\Worker;

use App\Models\Subscription;
use App\Services\MarketAPI\Exceptions\RateLimitException;
use App\Services\MarketAPI\MarketAPI;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckSubscriptionJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $subscription;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $status = MarketAPI::forSubscription($this->subscription)->checkSubscription();
        } catch (RateLimitException $e) {
            $status = MarketAPI::forSubscription($this->subscription)->checkSubscription();
        }
        if ($status === 'active') {
            $this->subscription->status = 'canceled';
            $this->subscription->save();
        }
    }
}
