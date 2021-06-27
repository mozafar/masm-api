<?php

namespace App\Services\Callback\Notifications;

use App\Services\Callback\CallbackAttributes;
use App\Services\Callback\CallbackChannel;
use App\Services\Callback\CallbackMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

class StatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 2;

    public $timeout = 15;

    private CallbackAttributes $attributes;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(CallbackAttributes $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [CallbackChannel::class];
    }

    public function toCallback($notifiable)
    {
        return CallbackMessage::create([
            'app_id' => $this->attributes->getAppId(),
            'device_id' => $this->attributes->getDeviceId(),
            'status' => $this->attributes->getStatus(),
        ]);
    }
}
