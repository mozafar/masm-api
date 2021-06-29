<?php

namespace App\Services\MarketAPI\MockAPI;

use App\Models\Subscription;
use App\Services\MarketAPI\Exceptions\RateLimitException;
use Faker\Factory as Faker;

class MarketAPIBase
{
    protected $subscription;

    public function __construct(?Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function verifyReceipt(string $receipt): bool|string
    {
        if ((intval(substr($receipt, -1, 1)) % 2) !== 0) {
            return $this->getRandomDate();
        }

        return false;
    }

    public function checkSubscription(?string $receipt = null): string
    {
        $receipt = $receipt ?? $this->subscription->receipt;
        
        if ((intval(substr($receipt, -1, 1)) % 6) === 0) {
            throw new RateLimitException(null);
        }
        $statusArray = ['active', 'canceled'];
        return $statusArray[array_rand($statusArray)];
    }

    protected function getRandomDate($tz = null): string
    {
        return Faker::create()
            ->dateTimeThisYear($max = now()->addYear(), $timezone = $tz)
            ->format('Y-m-d H:i:s');
    }
}
