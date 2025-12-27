<?php

namespace App\Filament\Resources\Cars\Tables;

use App\Filament\Tables\Columns\SliderColumn;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Support\Enums\FontWeight;

class CarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 3,
                'xl' => 3,
            ])

            ->columns([
                Grid::make(1)
                    ->schema([
                        Split::make([
                            SliderColumn::make('slider')
                                ->collection('car_images')
                                ->label(''),
                            Stack::make([
                                TextColumn::make('model.make.name')
                                    ->label('Make')
                                    ->weight(FontWeight::Bold),

                                TextColumn::make('model.name')
                                    ->label('Model'),

                                TextColumn::make('year')
                                    ->label('Year'),

                                TextColumn::make('vin')
                                    ->label('VIN')
                                    ->copyable(),

                                TextColumn::make('purchase_date')
                                    ->label('Purchase')
                                    ->icon('heroicon-o-calendar')
                                    ->date('M d, Y')
                                    ->sortable(),

                                TextColumn::make('arrival_date')
                                    ->label('Arrival')
                                    ->icon('heroicon-o-truck')
                                    ->date('M d, Y')
                                    ->sortable(),
                            ]),
                        ]),
                    ]),
            ])

            ->filters([
                TrashedFilter::make(),
            ])

            ->recordActions([
                EditAction::make()
                    ->label('რედაქტირება')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary'),

                DeleteAction::make()
                    ->label('წაშლა')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation(),
            ])

            ->recordCheckboxPosition(null)
            ->groupedBulkActions([])
            ->selectable(false)
            ->recordUrl(null)
            ->recordClasses('rounded-xl shadow-sm border border-gray-100 bg-white p-4')
            ->paginated(true);
    }
}
