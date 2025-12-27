<?php

namespace App\Filament\Resources\NotificationParameters\Pages;

use App\Filament\Resources\NotificationParameters\NotificationParameterResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateNotificationParameter extends CreateRecord
{
    protected static string $resource = NotificationParameterResource::class;

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
