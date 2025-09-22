<?php

namespace App\Providers;

use App\Interfaces\BalanceHistoryRepositoryInterface;
use App\Interfaces\BalanceRepositoryInterface;
use App\Repositories\BalanceHistoryRepository;
use App\Repositories\BalanceRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BalanceRepositoryInterface::class, BalanceRepository::class);
        $this->app->bind(BalanceHistoryRepositoryInterface::class, BalanceHistoryRepository::class);
    }
}
