<?php

namespace App\Services\Callback;

interface CallbackAttributes
{
    public function getAppId(): string;
    public function getDeviceId(): string;
    public function getStatus(): string;
}