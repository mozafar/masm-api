<?php

namespace App\Services\MarketAPI;

interface MarketAPIInterface
{
    public function verifyReceipt(string $receipt): bool|string;
    public function checkSubscription(?string $receipt = null): string;
}