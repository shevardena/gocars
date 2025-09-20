<?php

namespace App\Filament\Resources\CarModels\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CarModelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->default(null),
                TextInput::make('group')
                    ->default(null),
                TextInput::make('car_make_id')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
