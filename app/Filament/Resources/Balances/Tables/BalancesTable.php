<?php

namespace App\Filament\Resources\Balances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BalancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),

                TextColumn::make('author.first_name')
                    ->label('Author')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state, $record) => trim(($record->author?->first_name ?? '') . ' ' . ($record->author?->last_name ?? ''))
                    ),

                TextColumn::make('backend_user.first_name')
                    ->label('User')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state, $record) => trim(($record->backend_user?->first_name ?? '') . ' ' . ($record->backend_user?->last_name ?? ''))
                    ),
                TextColumn::make('amount_gel')
                    ->label('Amount GEL')
                    ->searchable(),
                TextColumn::make('usd_rate')
                    ->sortable(),
                TextColumn::make('amount_usd')
                    ->label('Amount USD')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Current amount')
                    ->sortable()
                ->formatStateUsing(fn ($state) => number_format($state, 0) . ' GEL'),
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
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
