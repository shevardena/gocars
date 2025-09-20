<?php

namespace App\Filament\Resources\CarMakes\Pages;

use App\Filament\Resources\CarMakes\CarMakeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCarMake extends ViewRecord
{
    protected static string $resource = CarMakeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
