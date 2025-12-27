<?php

namespace App\Filament\Resources\CarMakes\Pages;

use App\Filament\Resources\CarMakes\CarMakeResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateCarMake extends CreateRecord
{
    protected static string $resource = CarMakeResource::class;

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
