<?php

namespace App\Services\MarketAPI\MockAPI;

use App\Services\MarketAPI\MarketAPIInterface;

class GooglePlayAPI extends MarketAPIBase implements MarketAPIInterface
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
}
