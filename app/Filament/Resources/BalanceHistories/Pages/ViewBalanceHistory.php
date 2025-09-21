<?php

namespace App\Filament\Resources\BalanceHistories\Pages;

use App\Filament\Resources\BalanceHistories\BalanceHistoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBalanceHistory extends ViewRecord
{
    protected static string $resource = BalanceHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
