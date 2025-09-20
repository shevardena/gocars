<?php

namespace App\Filament\Resources\Cars\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('year')
                    ->numeric()
                    ->default(null),
                TextInput::make('vin')
                    ->default(null),
                DateTimePicker::make('arrival_date'),
                DateTimePicker::make('purchase_date'),
                Toggle::make('is_sold')
                    ->required(),
                Select::make('model_id')
                    ->relationship(name: 'model', titleAttribute: 'name')
                    ->searchable(),
                Section::make('Rate limiting')
                    ->description('Prevent abuse by limiting the number of requests per period')
                    ->columnSpan('full')
                    ->schema([
                        TextInput::make('phone')
                            ->tel()
                            ->default(null),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->default(null),
                    ])
            ]);
    }
}
