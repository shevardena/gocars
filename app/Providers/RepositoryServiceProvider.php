<?php

namespace App\Providers;

use App\Interfaces\BackendUserRepositoryInterface;
use App\Interfaces\BalanceHistoryRepositoryInterface;
use App\Interfaces\BalanceRepositoryInterface;
use App\Interfaces\CarRepositoryInterface;
use App\Interfaces\ExpenseRepositoryInterface;
use App\Models\BackendUser;
use App\Repositories\BackendUserRepository;
use App\Repositories\BalanceHistoryRepository;
use App\Repositories\BalanceRepository;
use App\Repositories\CarRepository;
use App\Repositories\ExpenseRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CarRepositoryInterface::class, CarRepository   ::class);
        $this->app->bind(BackendUserRepositoryInterface::class, BackendUserRepository::class);
        $this->app->bind(ExpenseRepositoryInterface::class, ExpenseRepository::class);
        $this->app->bind(BalanceRepositoryInterface::class, BalanceRepository::class);
        $this->app->bind(BalanceHistoryRepositoryInterface::class, BalanceHistoryRepository::class);
    }
}
