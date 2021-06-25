<?php

namespace App\Services\MarketAPI\MockAPI;

use App\Models\App;
use Faker\Factory as Faker;

class MarketAPIBase
{
    protected $app;

    public function __construct(?App $app) {
        $this->app = $app;
    }
    
    public function verifyReceipt(string $receipt): bool|string
    {
        if ((intval(substr($receipt, -1, 1)) % 2) !== 0) {
            return $this->getRandomDate();
        }

        return false;
    }

    public function verifySubscription()
    {

    }

    protected function getRandomDate($tz = null): string
    {
        return Faker::create()
            ->dateTimeThisYear($max = now()->addYear(), $timezone = $tz)
            ->format('Y-m-d H:i:s');
    }
}
