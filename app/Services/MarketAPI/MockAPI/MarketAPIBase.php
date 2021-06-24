<?php

namespace App\Services\MarketAPI\MockAPI;

use Faker\Factory as Faker;

class MarketAPIBase
{
    
    public function verifyReciept(string $reciept): bool|string
    {
        if ((intval(substr($reciept, 0, -1)) % 2) !== 0) {
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
