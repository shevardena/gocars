<?php

namespace App\Filament\Resources\Balances\Pages;

use App\Filament\Resources\Balances\BalanceResource;
use App\Services\BalanceService;
use Filament\Resources\Pages\CreateRecord;

class CreateBalance extends CreateRecord
{
    protected static string $resource = BalanceResource::class;

    public function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        app(BalanceService::class)->store($data);

        // Option 1: return a fresh record to Filament (for redirect/detail view)
        return static::getModel()::latest()->first();
    }
}
