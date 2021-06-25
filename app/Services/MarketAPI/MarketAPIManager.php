<?php

namespace App\Services\MarketAPI;

use App\Models\App;
use App\Models\OS;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Manager;

class MarketAPIManager extends Manager
{   
    protected $os;
    protected $app;

    public function forOS(OS $os)
    {
        $this->os = $os;
        return $this->driver($this->getStore());
    }

    public function forApp(App $app)
    {
        $this->app = $app;
        $this->os = $app->os;
        return $this->driver($this->getStore());
    }

    public function createAppStoreDriver(): MarketAPIInterface
    {
        $driver = $this->getConfigDriver();
        return resolve(config("market-api.$driver.app-store"), ['app' => $this->app]);
    }

    public function createGooglePlayDriver(): MarketAPIInterface
    {
        $driver = $this->getConfigDriver();
        return resolve(config("market-api.$driver.google-play"), ['app' => $this->app]);
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
