<?php

namespace App\Services\MarketAPI;

use App\Models\Subscription;
use Illuminate\Support\Manager;

class MarketAPIManager extends Manager
{
    protected $subscription;
    protected $os;

    public function forSubscription(?Subscription $subscription): MarketAPIInterface
    {
        $this->subscription = $subscription;
        $this->os = $subscription->app->os;

        return $this->driver($this->getStore());
    }

    public function createAppStoreDriver(): MarketAPIInterface
    {
        $driver = $this->getConfigDriver();

        return resolve(config("market-api.$driver.app-store"), ['subscription' => $this->subscription]);
    }

    public function createGooglePlayDriver(): MarketAPIInterface
    {
        $driver = $this->getConfigDriver();

        return resolve(config("market-api.$driver.google-play"), ['subscription' => $this->subscription]);
    }

    public function getDefaultDriver()
    {
        return null;
    }

    private function getConfigDriver(): string
    {
        return config('market-api.driver');
    }

    private function getStore(): ?string
    {
        switch ($this->os->name) {
            case 'iOS':
                return 'app-store';
                break;

            case 'Android':
                return 'google-play';
                break;
            default:
                return null;
                break;
        }
    }
}
