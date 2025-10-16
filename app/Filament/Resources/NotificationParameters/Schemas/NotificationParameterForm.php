<?php

namespace App\Filament\Resources\NotificationParameters\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class NotificationParameterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('parameter')
                    ->options(['phone' => 'Phone', 'email' => 'Email', 'day' => 'Day'])
                    ->required(),
                TextInput::make('value')
                    ->required(),
            ]);
    }
}
