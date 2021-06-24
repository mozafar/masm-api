<?php

namespace App\Services\MarketAPI;

interface MarketAPIInterface
{
    public function verifyReciept(string $reciept): bool|array;
    public function verifySubscription();
}