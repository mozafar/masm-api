<?php

namespace Tests\Unit;

use App\Models\Subscription;
use App\Services\MarketAPI\MarketAPI;
use Carbon\Carbon;

class MockMarketAPITest extends \Orchestra\Testbench\TestCase
{
    /** @test */
    public function should_return_expire_when_reciept_is_odd()
    {
        $response = MarketAPI::driver('google-play')->verifyReceipt('12345');
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $response);
        $this->assertEquals($date, $response);
    }

    /** @test */
    public function should_return_false_when_reciept_is_even()
    {
        $response = MarketAPI::driver('google-play')->verifyReceipt('1234');
        
        $this->assertFalse($response);
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'App\Services\MarketAPI\MarketAPIServiceProvider',
        ];
    }

}
