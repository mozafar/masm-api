<?php

namespace App\Services\MarketAPI;

use App\Services\MarketAPI\MockAPI\MockMarketAPI;
use Illuminate\Support\Manager;

class MarketAPIManager extends Manager
{   
    public function driver($driver = null)
    {
        return parent::driver($driver);
    }

    public function createMockDriver()
    {
        return resolve(MockMarketAPI::class);
    }

    public function createProductionDriver()
    {
        return resolve(ProductionMarketAPI::class);
    }

    public function getDefaultDriver()
    {
        return config('market-api.driver');
    }
}
