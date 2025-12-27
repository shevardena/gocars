<?php

namespace App\Filament\Resources\Cars\Schemas;

use App\Models\CarMake;
use App\Models\CarModel;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class CarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Car Images Preview')
                    ->schema([
                        Placeholder::make('car_images_preview')
                            ->content(fn ($record) =>
                            $record
                                ? new HtmlString(
                                view('filament.forms.car-images-preview', [
                                    'record' => $record,
                                ])->render()
                            )
                                : ''
                            )
                            ->columnSpan('full'),
                    ])
                    ->visible(fn ($record) => filled($record))
                    ->columnSpan('full'),
                Select::make('car_make_id')
                    ->label('Car Make')
                    ->required()
                    ->options(CarMake::query()->pluck('name', 'id'))
                    ->live()
                    ->searchable()
                    ->afterStateHydrated(function ($state, callable $set, $record) {
                        if ($record?->car_model_id) {
                            $set(
                                'car_make_id',
                                $record->model?->car_make_id
                            );
                        }
                    }),
                Select::make('car_model_id')
                    ->label('Model')
                    ->required()
                    ->options(fn (Get $get): Collection =>
                    CarModel::query()
                        ->where('car_make_id', $get('car_make_id'))
                        ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->afterStateHydrated(function (callable $set, $record) {
                        if ($record?->car_model_id) {
                            $set('car_model_id', $record->car_model_id);
                        }
                    }),

                TextInput::make('year')
                    ->numeric()
                    ->required()
                    ->default(null),
                TextInput::make('vin')
                    ->required()
                    ->default(null),
                DateTimePicker::make('purchase_date'),
                DateTimePicker::make('arrival_date'),
                Toggle::make('is_sold')
                    ->required(),
                TextInput::make('sold_price_usd')
                    ->numeric()
                    ->label('Sold Price USD')
                    ->default(null),
                Tabs::make('Car Extra Info')
                    ->tabs([
                        Tab::make('Notifications')
                            ->schema([
                                TextInput::make('phone')
                                    ->tel()
                                    ->rule('regex:/^(\+995\d{9}|\d{9})$/')
                                    ->helperText('Format: +995XXXXXXXXX or XXXXXXXXX')
                                    ->default(null),
                                TextInput::make('email')
                                    ->label('Email address')
                                    ->email()
                                    ->default(null),
                            ])
                            ->columns(2),

                        Tab::make('Images')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('images')
                                    ->columnSpan('full')
                                    ->collection('car_images')
                                    ->multiple()
                                    ->reorderable(),
                            ]),
                    ])
                    ->columnSpan('full'),

                Section::make('Financial Overview')
                    ->schema([
                        Placeholder::make('total_expenses_gel')
                            ->label('Total Expenses (GEL)')
                            ->content(fn ($record) =>
                            number_format($record?->expenses()->sum('amount_gel') ?? 0, 2)
                            ),

                        Placeholder::make('total_expenses_usd')
                            ->label('Total Expenses (USD)')
                            ->content(fn ($record) =>
                            number_format($record?->expenses()->sum('amount_usd') ?? 0, 2)
                            ),

                        TextInput::make('sold_price_usd')
                            ->label('Sold Amount (USD)')
                            ->numeric()
                            ->live()
                            ->visible(fn (Get $get) =>
                                $get('is_sold') && auth()->user()->is_admin
                            )
                            ->required(fn (Get $get) =>
                                $get('is_sold') && auth()->user()->is_admin
                            ),

                        Placeholder::make('profit_usd')
                            ->label('Profit (USD)')
                            ->content(function (Get $get, $record) {
                                if (!$get('is_sold')) {
                                    return '—';
                                }

                                $soldPrice = (float) ($get('sold_price_usd') ?? 0);

                                $expensesUsd = (float) (
                                    $record?->expenses()->sum('amount_usd') ?? 0
                                );

                                return number_format($soldPrice - $expensesUsd, 2);
                            })
                            ->live()
                            ->extraAttributes([
                                'class' => 'font-bold text-success-600',
                            ]),

                    ])
                    ->columns(3),

            ]);
    }
}
