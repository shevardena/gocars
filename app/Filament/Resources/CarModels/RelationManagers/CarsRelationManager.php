<?php

namespace App\Filament\Resources\CarModels\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CarsRelationManager extends RelationManager
{
    protected static string $relationship = 'cars';

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ])->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('model.make.name')
                    ->label('Make')
                    ->sortable(),
                TextColumn::make('model.name')
                    ->label('Model')
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('year')
                    ->sortable(),
                TextColumn::make('vin')
                    ->searchable(),
                TextColumn::make('purchase_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('arrival_date')
                    ->dateTime()
                    ->sortable(),
                ToggleColumn::make('is_sold'),
                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);;
    }


    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
