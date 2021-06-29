<?php

namespace App\Services\Callback;

interface CallbackSubject
{
    public function callbackUrl(): string;
}