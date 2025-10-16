<?php

use Illuminate\Support\Facades\Route;
use App\Services\ExchangeRateService;

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function () {
    $rate =ExchangeRateService::fetchAndCacheUsdRate();
    dd($rate);
});
