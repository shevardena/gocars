<?php

namespace App\Classes;

class BalanceCalculator
{
    public function getCachedUsdRate(): float
    {
        return cache()->remember('usd_rate', 3600, function () {
            return config('rates.usd_rate');
        });
    }

    public function calculateAmountGel(float $gelAmount, float $usdRate): float
    {
        return $gelAmount;
    }

    /**
     * Convert GEL to USD using rate
     */
    public function gelToUsd(float $gelAmount, float $usdRate): float
    {
        if ($usdRate <= 0) {
            throw new \InvalidArgumentException('Invalid USD rate');
        }

        return round($gelAmount / $usdRate, 2);
    }

    /**
     * Normalize GEL amount
     */
    public function normalizeGel(float $gelAmount): float
    {
        return round($gelAmount, 2);
    }
}
