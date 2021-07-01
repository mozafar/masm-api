<?php

namespace App\Services\Callback;

use App\Services\Callback\Exceptions\CallbackFailedException;
use App\Services\Callback\Exceptions\CallbackUrlNotSetException;
use Illuminate\Http\Client\Response;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class CallbackChannel
{
    /**
     * Send web request to given notifiable
     * 
     * @throws CallbackFailedException
     */
    public function send($notifiable, Notification $notification): Response
    {
        if (empty($url = $notifiable->callbackUrl())) {
            throw new CallbackUrlNotSetException(null);
        }

        $callbackData = $notification->toCallback($notifiable)->toArray();

        $response = Http::withHeaders(Arr::get($callbackData, 'headers'))
            ->post($url, Arr::get($callbackData, 'data'));

        if (! in_array($response->status(), [200, 201])) {
            throw CallbackFailedException::callbackRespondedWithAnError($response);
        }

        return $response;
    }
}