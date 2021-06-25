<?php

namespace App\Services\MarketAPI;

interface MarketAPIInterface
{
    public function verifyReceipt(string $reciept): bool|string;
    public function verifySubscription();
}