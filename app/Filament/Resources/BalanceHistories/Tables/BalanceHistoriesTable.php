<?php

namespace App\Filament\Resources\BalanceHistories\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class BalanceHistoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('author.first_name')
                    ->label('Author')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state, $record) =>
                    trim(($record->author?->first_name ?? '') . ' ' . ($record->author?->last_name ?? ''))
                    ),

                TextColumn::make('backend_user.first_name')
                    ->label('User')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state, $record) =>
                    trim(($record->backend_user?->first_name ?? '') . ' ' . ($record->backend_user?->last_name ?? ''))
                    ),

                TextColumn::make('operation_type')
                    ->label('Operation Type')
                    ->sortable()
                    ->badge() // optional for nice colored labels
                    ->colors([
                        'success' => 'deposit',
                        'danger' => 'expense',
                    ]),

                TextColumn::make('amount_usd')
                    ->label('Amount in USD')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state . ' USD'),

                TextColumn::make('usd_rate')
                    ->label('USD Rate')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => '1 USD = ' . ($state ?: 0) . ' GEL'),

                TextColumn::make('amount_gel')
                    ->label('Amount in GEL')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => ($state ?: 0) . ' GEL'),
            ])
            ->filters([
                SelectFilter::make('operation_type')
                    ->label('Operation Type')
                    ->options([
                        'deposit' => 'Deposit',
                        'expense' => 'Expense',
                    ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }
}
