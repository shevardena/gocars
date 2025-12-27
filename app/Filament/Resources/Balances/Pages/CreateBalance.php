<?php

namespace App\Filament\Resources\Balances\Pages;

use App\Filament\Resources\Balances\BalanceResource;
use App\Services\BalanceService;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateBalance extends CreateRecord
{
    protected static string $resource = BalanceResource::class;

    public function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        app(BalanceService::class)->store($data);
        return static::getModel()::latest()->first();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->submit('create')
                ->color('primary'),

            Action::make('cancel')
                ->url($this->getResource()::getUrl())
                ->color('gray'),
        ];
    }
}
