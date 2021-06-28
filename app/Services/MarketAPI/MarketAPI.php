<?php

namespace App\Services\MarketAPI;

/**
 * @method static mixed function verifyReciept(string $reciept)
 * @method static void function checkSubscription()
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