<?php

namespace App\Services\MarketAPI\MockAPI;

use Carbon\CarbonTimeZone;
use App\Services\MarketAPI\MarketAPIInterface;
use Illuminate\Support\Facades\Log;

class AppStoreAPI extends MarketAPIBase implements MarketAPIInterface
{
    public function verifyReceipt(string $receipt): bool|string
    {
        if ((intval(substr($receipt, -1, 1)) % 2) !== 0) {
            return $this->getRandomDate(CarbonTimeZone::create(-6)->toRegionName());
        }

        return false;
    }
}
