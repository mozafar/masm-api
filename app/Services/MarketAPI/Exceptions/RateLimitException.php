<?php

namespace App\Services\MarketAPI\Exceptions;

use Exception;

class RateLimitException extends Exception
{
    public function __construct(?string $message)
    {
        parent::__construct($message ?? 'You hit the market rate limit', 500);
    }
}
