<?php

namespace App\Filament\Resources\CarMakes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CarMakeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->default(null),
            ]);
    }
}
