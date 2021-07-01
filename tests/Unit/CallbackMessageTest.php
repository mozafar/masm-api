<?php

namespace Tests\Unit;

use App\Services\Callback\CallbackMessage;
use Illuminate\Support\Arr;
use Tests\TestCase;

class CallbackMessageTest extends TestCase
{
    protected CallbackMessage $message;

    public function setUp(): void
    {
        parent::setUp();
        $this->message = new CallbackMessage();
    }

    /** @test */
    public function it_accepts_data_when_constructing_a_message()
    {
        $message = new CallbackMessage(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], Arr::get($message->toArray(), 'data'));
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = CallbackMessage::create(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], Arr::get($message->toArray(), 'data'));
    }

    /** @test */
    public function it_can_set_the_webhook_data()
    {
        $this->message->data(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], Arr::get($this->message->toArray(), 'data'));
    }

    /** @test */
    public function it_can_set_a_custom_header()
    {
        $this->message->header('X-Custom', 'Value');
        $this->assertEquals(['X-Custom' => 'Value'], Arr::get($this->message->toArray(), 'headers'));
    }
}
