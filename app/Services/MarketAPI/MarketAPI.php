<?php

namespace App\Services\MarketAPI;

/**
 * @method static \App\Services\MarketAPI\MarketAPIInterface forSubscription(?\App\Models\Subscription $subscription)
 */

use Illuminate\Support\Facades\Facade;

class MarketAPI extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return resolve(MarketAPIManager::class);
    }
}