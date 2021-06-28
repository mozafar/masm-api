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

    public function checkSubscription(?string $receipt): string
    {
        $receipt = $receipt ?? $this->subscription->receipt;
        if ($receipt % 4) {
            throw new RateLimitException(null);
        }
        return array_rand(['expired', 'canceled']);
    }

    protected function getRandomDate($tz = null): string
    {
        return Faker::create()
            ->dateTimeThisYear($max = now()->addYear(), $timezone = $tz)
            ->format('Y-m-d H:i:s');
    }
}
