<?php

namespace App\Filament\Resources\Cars\Schemas;

use App\Models\Car;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;

class CarInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('model.make.name')
                    ->label('Make')
                    ->placeholder(''),
                TextEntry::make('model.name')
                    ->label('Model')
                    ->placeholder(''),
                TextEntry::make('slug')
                    ->placeholder(''),
                TextColumn::make('false'),
                TextEntry::make('year')
                    ->placeholder(''),
                TextEntry::make('vin')
                    ->placeholder(''),
                TextEntry::make('purchase_date')
                    ->dateTime()
                    ->placeholder(''),
                TextEntry::make('arrival_date')
                    ->dateTime()
                    ->placeholder(''),
                IconEntry::make('is_sold')
                    ->boolean(),
                TextColumn::make('false'),
                TextEntry::make('phone')
                    ->placeholder(''),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder(''),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Car $record): bool => $record->trashed()),
                ImageEntry::make('colleagues.images')->stacked()
            ]);
    }
}
