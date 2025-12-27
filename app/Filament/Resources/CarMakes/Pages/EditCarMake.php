<?php

namespace App\Filament\Resources\CarMakes\Pages;

use App\Filament\Resources\CarMakes\CarMakeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCarMake extends EditRecord
{
    protected static string $resource = CarMakeResource::class;
}
