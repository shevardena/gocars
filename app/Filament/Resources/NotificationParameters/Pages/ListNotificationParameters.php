<?php

namespace App\Filament\Resources\NotificationParameters\Pages;

use App\Filament\Resources\NotificationParameters\NotificationParameterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNotificationParameters extends ListRecords
{
    protected static string $resource = NotificationParameterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
