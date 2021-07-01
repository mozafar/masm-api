<?php

namespace App\Services\MarketAPI;

interface MarketAPIInterface
{
    /**
     * Verifies given receipt on API
     */
    public function verifyReceipt(string $receipt): bool|string;

    /**
     * Checks subscription status and returns status
     */
    public function checkSubscription(?string $receipt = null): string;
}