<?php

namespace App\Filament\Resources\Expenses\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('car.model.make.name')
                    ->label('Make')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('car.model.name')
                    ->label('Model')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('car.year')
                    ->label('Year')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('car.vin')
                    ->label('VIN')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('author.first_name')
                    ->label('Author')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state, $record) =>
                    trim(($record->author?->first_name ?? '') . ' ' . ($record->author?->last_name ?? ''))
                    ),

                TextColumn::make('title')
                    ->label('Title')
                    ->searchable(),

                TextColumn::make('amount_gel')
                    ->label('Amount (GEL)')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => round(floatval($state), 2) . ' GEL'),

                TextColumn::make('amount_usd')
                    ->label('Amount (USD)')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => round(floatval($state), 2) . ' USD'),
            ])
            ->filters([
                // Example: filter by operation type if you have it
                SelectFilter::make('operation_type')
                    ->label('Operation Type')
                    ->options([
                        'expense' => 'Expense',
                        'refund' => 'Refund',
                    ]),
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
            ]);
    }
}
