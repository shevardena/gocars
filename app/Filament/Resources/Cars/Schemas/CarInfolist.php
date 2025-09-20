<?php

namespace App\Filament\Resources\Cars\Schemas;

use App\Models\Car;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CarInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('slug')
                    ->placeholder('-'),
                TextEntry::make('year')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('vin')
                    ->placeholder('-'),
                TextEntry::make('arrival_date')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('purchase_date')
                    ->dateTime()
                    ->placeholder('-'),
                IconEntry::make('is_sold')
                    ->boolean(),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('car_model_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Car $record): bool => $record->trashed()),
            ]);
    }
}
