<?php

namespace App\Services\Callback\Exceptions;

use Exception;

class CallbackUrlNotSetException extends Exception
{
    public function __construct(?string $message)
    {
        parent::__construct($message ?? 'Url not set for notifiable', 500);
    }
}
