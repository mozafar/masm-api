<?php

namespace App\Services\MarketAPI\MockAPI;

use Carbon\CarbonTimeZone;
use App\Services\MarketAPI\MarketAPIInterface;

class AppStoreAPI extends MarketAPIBase implements MarketAPIInterface
{
    public function verifyReciept(string $reciept): bool|string
    {
        if ((intval(substr($reciept, 0, -1)) % 2) !== 0) {
            return $this->getRandomDate(CarbonTimeZone::create(-6)->toRegionName());
        }

        return false;
    }

    public function verifySubscription()
    {

    }
}
