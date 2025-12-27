<?php

namespace App\Filament\Resources\Expenses\Pages;

use App\Filament\Resources\Expenses\ExpensesResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateExpenses extends CreateRecord
{
    protected static string $resource = ExpensesResource::class;

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
