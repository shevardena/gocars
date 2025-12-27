<?php

namespace App\Filament\Resources\Expenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
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
                    ->formatStateUsing(fn($state, $record) => trim(($record->backend_user?->first_name ?? '') . ' ' . ($record->backend_user?->last_name ?? ''))),

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
                SelectFilter::make('operation_type')
                    ->label('Operation Type')
                    ->options([
                        'expense' => 'Expense',
                        'refund' => 'Refund',
                    ]),
            ])
            ->recordActions([
                EditAction::make()->label('რედაქტირება'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->recordClasses(function ($record) {
                // Check if record exists and has the required relationships
                if (!$record || !$record->backend_user?->parameter?->expense_color) {
                    return null;
                }

                return 'custom-bg-row';
            })
            ->extraAttributes(function ($record) {
                // Check if record exists and has the required relationships
                if (!$record || !$record->backend_user?->parameter?->expense_color) {
                    return [];
                }

                $color = $record->backend_user->parameter->expense_color;

                // Add 1A for 10% opacity, or adjust as needed
                $bgColor = $color . '1A';

                return [
                    'style' => "background-color: {$bgColor};"
                ];
            });
    }

    /**
     * Simple luminance check for dark colors
     */
    protected static function isDarkColor(string $hex): bool
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = preg_replace('/(.)/', '$1$1', $hex);
        }
        [$r, $g, $b] = [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
        $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
        return $brightness < 155;
    }
}
