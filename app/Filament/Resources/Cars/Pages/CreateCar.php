<?php

namespace App\Filament\Resources\Cars\Pages;

use App\Filament\Resources\Cars\CarResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateCar extends CreateRecord
{
    protected static string $resource = CarResource::class;

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
