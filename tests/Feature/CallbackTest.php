<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CallbackTest extends TestCase
{
    /** @test */
    public function it_should_send_callback_notification()
    {
        Notification::fake();

        
    }
}
