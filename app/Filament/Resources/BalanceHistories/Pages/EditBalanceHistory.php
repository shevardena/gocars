<?php

namespace App\Filament\Resources\BalanceHistories\Pages;

use App\Filament\Resources\BalanceHistories\BalanceHistoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBalanceHistory extends EditRecord
{
    protected static string $resource = BalanceHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
