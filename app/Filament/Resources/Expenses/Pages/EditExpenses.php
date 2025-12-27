<?php

namespace App\Filament\Resources\Expenses\Pages;

use App\Filament\Resources\Expenses\ExpensesResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditExpenses extends EditRecord
{
    protected static string $resource = ExpensesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->label('წაშლა'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('შენახვა')
                ->submit('save') // 🔥 calls save() internally
                ->keyBindings(['mod+s'])
                ->color('primary'),

            Action::make('cancel')
                ->label('გაუქმება')
                ->url($this->getResource()::getUrl())
                ->color('gray'),
        ];
    }
}
