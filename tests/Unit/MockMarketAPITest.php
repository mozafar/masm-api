<?php

namespace Tests\Unit;

use App\Services\MarketAPI\Exceptions\RateLimitException;
use App\Services\MarketAPI\MarketAPI;
use Carbon\Carbon;
use Orchestra\Testbench\TestCase;

class MockMarketAPITest extends TestCase
{
    /** @test */
    public function it_return_expire_when_reciept_is_odd()
    {
        $response = MarketAPI::driver('google-play')->verifyReceipt('12345');
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $response);
        $this->assertEquals($date, $response);
    }

    /** @test */
    public function it_return_false_when_reciept_is_even()
    {
        $response = MarketAPI::driver('google-play')->verifyReceipt('1234');
        
        $this->assertFalse($response);
    }

    /** @test */
    public function it_return_subscription_status()
    {
        $response = MarketAPI::driver('google-play')->checkSubscription('123');
        
        $this->assertContains($response, ['pending', 'active', 'canceled']);
    }

    /** @test */
    public function it_throws_rate_limit_exception_when_devide_receipt_by_6()
    {
        $this->expectException(RateLimitException::class);
        $this->expectExceptionCode(500);

        MarketAPI::driver('google-play')->checkSubscription('123456');
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
