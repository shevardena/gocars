<?php

namespace App\Filament\Resources\Expenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $user = Auth::user();

                if (!$user->super_admin) {
                    $query->where('backend_user_id', $user->id);
                }
                $query->orderByDesc('created_at');
            })
            ->columns([
                TextColumn::make('car')
                    ->label('Car')
                    ->html()
                    ->formatStateUsing(function ($state, $record) {
                        $car = $record->car;

                        if (!$car) {
                            return '-';
                        }

                        $make = e($car->model->make->name ?? '-');
                        $model = e($car->model->name ?? '-');
                        $year = e($car->year ?? '-');
                        $vin = e($car->vin ?? '-');

                        return <<<HTML
                            <div style="line-height:1.4">
                                {$make} {$model} {$year}<br>
                                <small>VIN:</small> <strong>{$vin}</strong>
                            </div>
                        HTML;
                    })
                    ->searchable(['car.model.make.name', 'car.model.name', 'car.vin']),
                TextColumn::make('backend_user.first_name')
                    ->label('Author')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state, $record) => trim(($record->backend_user?->first_name ?? '') . ' ' . ($record->backend_user?->last_name ?? ''))
                    ),

                TextColumn::make('title')
                    ->label('Title')
                    ->searchable(),

                TextColumn::make('amount_gel')
                    ->label('Amount (GEL)')
                    ->sortable()
                    ->formatStateUsing(fn($state) => round(floatval($state), 2) . ' GEL'),

                TextColumn::make('amount_usd')
                    ->label('Amount (USD)')
                    ->sortable()
                    ->formatStateUsing(fn($state) => round(floatval($state), 2) . ' USD'),
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
