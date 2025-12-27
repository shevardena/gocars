<?php

namespace App\Filament\Resources\CarModels\Pages;

use App\Filament\Resources\CarModels\CarModelResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateCarModel extends CreateRecord
{
    protected static string $resource = CarModelResource::class;

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
