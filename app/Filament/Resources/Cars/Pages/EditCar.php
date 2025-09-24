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

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->visible(fn () => auth()->user()->can('cars.view')),
            DeleteAction::make()->visible(fn () => auth()->user()->can('cars.update')),
            ForceDeleteAction::make()->visible(fn () => auth()->user()->can('cars.update')),
            RestoreAction::make()->visible(fn () => auth()->user()->can('cars.update')),

        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->visible(fn () => auth()->user()->can('cars.update')),
            $this->getCancelFormAction()->visible(fn () => auth()->user()->can('cars.update')),
        ];
    }
}
