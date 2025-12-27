<?php

namespace App\Classes;

class BalanceCalculator
{
    public function getCachedUsdRate(): float
    {
        return cache()->remember('usd_rate', 3600, function () {
            return config('rates.usd_rate'); // or fetch from db/api
        });
    }

    public function calculateAmountGel(float $gelAmount, float $usdRate): float
    {
        return $gelAmount;
    }
}
