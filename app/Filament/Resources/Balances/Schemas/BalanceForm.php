<?php

namespace App\Filament\Resources\Balances\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BalanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('backend_user_id')
                    ->label('User')
                    ->required()
                    ->relationship(
                        name: 'author',
                        modifyQueryUsing: fn (Builder $query) => $query->orderBy('first_name')->orderBy('last_name'),
                    )
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->first_name} {$record->last_name}")
                    ->searchable(['first_name', 'last_name']),
                TextColumn::make('false'),
                TextInput::make('amount_usd')
                    ->label('Amount USD')
                    ->required(),
                TextInput::make('usd_rate')
                    ->label('USD Rate')
                    ->required(),
            ]);
    }
}
