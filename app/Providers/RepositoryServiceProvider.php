<?php

namespace App\Providers;

use App\Interfaces\CarMakeRepositoryInterface;
use App\Repositories\CarMakeRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }
}
