<?php

namespace App\Services\Callback;

use App\Models\App;
use App\Models\Subscription;
use App\Services\Callback\Notifications\StatusChanged;
use Closure;
use Exception;

trait SendCallback
{
    private static function sendCallback(): Closure
    {
        return function (Subscription $subscription) {
            $interfaces = class_implements($subscription);
            if (! in_array(CallbackAttributes::class, $interfaces)) {
                throw new Exception('Callback observable should implement CallbackAttributes interface');
                
            }
    
            if (! isset($subscription->getDirty()['status'])) {
                return;
            }
    
            /** @var App*/
            $notifiable = $subscription->app()->first();
            $notifiable->notify(new StatusChanged($subscription));
        };
    }
}