<?php

namespace App\Services\Callback;

interface CallbackSubject
{
    /**
     * Get url to send web request.
     */
    public function callbackUrl(): string;
}
