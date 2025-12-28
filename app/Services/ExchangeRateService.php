<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeRateService
{
    /**
     * Fetch latest USD→GEL rate from TBC API and cache it.
     */
    public static function fetchAndCacheUsdRate(): ?float
    {
        $url = config('services.tbc_exchange.url');
        $apiKey = config('services.tbc_exchange.key');

        if (! $apiKey) {
            Log::error('TBC_API_KEY missing');
            return null;
        }

        $response = Http::withHeaders(['apikey' => $apiKey])
            ->timeout(10)
            ->get($url, ['currency' => 'usd']);


        if ($response->failed()) {
            Log::error('TBC API request failed', ['body' => $response->body()]);
            return null;
        }

        $data = $response->json();
        $rate = $data['commercialRatesList'][0]['sell'] ?? null;

        if (!$rate) {
            Log::error('USD rate missing in TBC API response');
            return null;
        }

        Cache::put('usd_rate', $rate, now()->addMinutes(10));
        return $rate;
    }

    /**
     * Get current USD→GEL rate (from cache or fresh fetch).
     */
    public static function getUsdRate(): float
    {
        $rate = Cache::get('usd_rate');

        if ($rate) {
            return (float) $rate;
        }

        $rate = self::fetchAndCacheUsdRate();

        if (! $rate) {
            throw new \RuntimeException('USD rate unavailable');
        }

        return (float) $rate;
    }
}
