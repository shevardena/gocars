<?php

namespace App\Filament\Resources\NotificationParameters\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NotificationParametersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('parameter')
                    ->label('Parameter Type')
                    ->sortable()
                    ->badge()
                    ->colors([
                        'success' => 'phone',
                        'danger' => 'email',
                        'warning' => 'day',
                    ]),
                TextColumn::make('value')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('parameter')
                    ->label('Parameter Type')
                    ->options([
                        'phone' => 'Phone',
                        'email' => 'Email',
                        'day' => 'Day',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
