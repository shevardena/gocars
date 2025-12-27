<?php

namespace App\Filament\Resources\Cars\Pages;

use App\Filament\Resources\Cars\CarResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCar extends EditRecord
{
    protected static string $resource = CarResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->visible(fn () => auth()->user()->can('cars.update'))->label('შენახვა'),
            $this->getCancelFormAction()->visible(fn () => auth()->user()->can('cars.update'))->label('გაუქმება'),
        ];
    }
}
