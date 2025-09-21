<?php

namespace App\Classes;

class BalanceCalculator
{
    /**
     * Calculate amount in gel based given usd amount and rate
     * @param float $amount_usd
     * @param float $usd_rate
     * @return float
     */
    public function calculateAmountGel(float $amount_usd, float $usd_rate): float
    {
        return $amount_usd * $usd_rate;
    }
}
