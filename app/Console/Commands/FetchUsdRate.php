<?php

namespace App\Console\Commands;

use App\Services\ExchangeRateService;
use Illuminate\Console\Command;

class FetchUsdRate extends Command
{
    protected $signature = 'exchange:usd';
    protected $description = 'Fetch and cache USD↔GEL exchange rate from TBC Bank';

    public function handle(): void
    {
        $rate = ExchangeRateService::fetchAndCacheUsdRate();

        if ($rate) {
            $this->info("✅ USD rate cached: {$rate}");
        } else {
            $this->error('❌ Failed to update USD rate');
        }
    }
}
