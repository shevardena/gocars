<?php

namespace App\Filament\Resources\Cars\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;

class CarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('model_id')
                    ->label('Model')
                    ->required()
                    ->relationship(name: 'model', titleAttribute: 'name')
                    ->searchable(),
                TextColumn::make('false'),
                TextInput::make('year')
                    ->numeric()
                    ->required()
                    ->default(null),
                TextInput::make('vin')
                    ->required()
                    ->default(null),
                DateTimePicker::make('purchase_date'),
                DateTimePicker::make('arrival_date'),
                Toggle::make('is_sold')
                    ->required(),
                Tabs::make('Car Extra Info')
                    ->tabs([
                        Tab::make('Notifications')
                            ->schema([
                                TextInput::make('phone')
                                    ->tel()
                                    ->rule('regex:/^(\+995\d{9}|\d{9})$/')
                                    ->helperText('Format: +995XXXXXXXXX or XXXXXXXXX')
                                    ->default(null),
                                TextInput::make('email')
                                    ->label('Email address')
                                    ->email()
                                    ->default(null),
                            ])
                            ->columns(2),

                        Tab::make('Images')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('images')
                                    ->columnSpan('full')
                                    ->collection('car_images')
                                    ->multiple()
                                    ->reorderable(),
                            ]),
                    ])
                    ->columnSpan('full'),
        ]);
    }
}
