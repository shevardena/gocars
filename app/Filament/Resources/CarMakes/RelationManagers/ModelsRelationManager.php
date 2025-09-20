<?php

namespace App\Filament\Resources\CarMakes\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ModelsRelationManager extends RelationManager
{
    protected static string $relationship = 'models';

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ])->columns([
                TextColumn::make('id')->label('ID'),
                ImageColumn::make('logo'),
                TextColumn::make('name'),
                TextColumn::make('slug'),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->default(null),
                TextInput::make('group'),
                TextColumn::make('null'),
                SpatieMediaLibraryFileUpload::make('logo'),
            ]);
    }
}
