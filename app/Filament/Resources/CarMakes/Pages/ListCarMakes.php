<?php

namespace App\Filament\Resources\CarMakes\Pages;

use App\Filament\Resources\CarMakes\CarMakeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCarMakes extends ListRecords
{
    protected static string $resource = CarMakeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
