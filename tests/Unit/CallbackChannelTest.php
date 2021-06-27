<?php

namespace Tests\Unit;

use Illuminate\Notifications\Notification;
use App\Services\Callback\Exceptions\CallbackFailedException;
use App\Services\Callback\CallbackChannel;
use App\Services\Callback\CallbackMessage;
use App\Services\Callback\CallbackSubject;
use App\Services\Callback\Exceptions\CallbackUrlNotSetException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CallbackChannelTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Http::fake([
            'https://test-callback-url.com/200' => Http::response(),
            'https://test-callback-url.com/201' => Http::response(null, 201),
            'https://test-callback-url.com/fail' => Http::response(null, 500)
        ]);
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $channel = new CallbackChannel();
        $response = $channel->send(new TestNotifiable('https://test-callback-url.com/200'), new TestNotification());
        $this->assertEquals(200, $response->status());
    }

    /** @test */
    public function it_can_send_a_notification_with_2xx_status()
    {
        $channel = new CallbackChannel();
        $response = $channel->send(new TestNotifiable('https://test-callback-url.com/201'), new TestNotification());
        $this->assertEquals(201, $response->status());
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_it_could_not_send_the_notification()
    {

        $this->expectException(CallbackFailedException::class);
        $this->expectExceptionMessage('Callback responded with an error: ``');
        $this->expectExceptionCode(500);

        $channel = new CallbackChannel();
        $response = $channel->send(new TestNotifiable('https://test-callback-url.com/fail'), new TestNotification());
        $this->assertEquals(500, $response->status());
    }


    /**
     * @test
     */
    public function it_throws_an_exception_when_url_is_empty()
    {

        $this->expectException(CallbackUrlNotSetException::class);
        $this->expectExceptionCode(500);

        $channel = new CallbackChannel();
        $response = $channel->send(new TestNotifiable(), new TestNotification());
        $this->assertEquals(500, $response->status());
    }
}

class TestNotifiable implements CallbackSubject
{
    use \Illuminate\Notifications\Notifiable;

    private $url;

    public function __construct($url = '')
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function callbackUrl(): string
    {
        return $this->url;
    }
}

class TestNotification extends Notification
{
    public function toCallback($notifiable)
    {
        return
            (new CallbackMessage(
                [
                    'status' => 'active',
                ]
            ))->header('X-Custom', 'CustomHeader');
    }
}