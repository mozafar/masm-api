<?php

namespace App\Services\Callback\Worker;

use App\Models\Subscription;
use App\Services\MarketAPI\Exceptions\RateLimitException;
use App\Services\MarketAPI\MarketAPI;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CSubscriptions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $from;
    private int $to;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $from, int $to)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Subscription::with('app')
            ->expired()
            ->offset($this->from)
            ->limit($this->to)
            ->each(function ($subscription) {
                try {
                    $status = MarketAPI::forSubscription($subscription)->checkSubscription();
                } catch (RateLimitException $e) {
                    $status = MarketAPI::forSubscription($subscription)->checkSubscription();
                }
                if ($status === 'expired') {
                    $subscription->status = 'canceled';
                    $subscription->save();
                }
            });
    }
}
