<?php

namespace App\Services\MarketAPI\MockAPI;

use App\Services\MarketAPI\MarketAPIInterface;
use Illuminate\Support\Manager;

class MockMarketAPI extends Manager
{   
    public function driver($driver = null)
    {
        return parent::driver($driver);
    }

    public function createAppStoreDriver(): MarketAPIInterface
    {
        return resolve(config('market-api.mock.app-store'));
    }

    public function createGooglePlayDriver(): MarketAPIInterface
    {
        return resolve(config('market-api.mock.google-play'));
    }

    public function getDefaultDriver()
    {
        return null;
    }
}
