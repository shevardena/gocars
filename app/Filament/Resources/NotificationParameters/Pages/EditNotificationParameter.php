<?php

namespace App\Filament\Resources\NotificationParameters\Pages;

use App\Filament\Resources\NotificationParameters\NotificationParameterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNotificationParameter extends EditRecord
{
    protected static string $resource = NotificationParameterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
