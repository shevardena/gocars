<?php

namespace App\Filament\Resources\CarModels\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;

class CarModelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('car_make_id')
                    ->label('Make')
                    ->required()
                    ->relationship(name: 'make', titleAttribute: 'name')
                    ->searchable(),
                TextColumn::make('false'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->default(null),
                TextInput::make('group')
                    ->default(null),
            ]);
    }
}
