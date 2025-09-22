<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use App\Models\Car;

class ExpensesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('car_id')
                    ->label('Car')
                    ->options(
                        Car::query()
                            ->with(['model.make'])
                            ->get()
                            ->mapWithKeys(fn ($car) => [
                                $car->id => ($car->model->make->name ?? '') . ' ' .
                                    ($car->model->name ?? '') . ' / ' .
                                    $car->year . ' / ' .
                                    $car->vin,
                            ])
                    )
                    ->searchable()
                    ->required(),

                TextInput::make('title')
                    ->label('Title')
                    ->required(),

                TextInput::make('amount_gel')
                    ->label('Amount GEL')
                    ->visible(!Auth::user()?->super_admin),

                TextInput::make('amount_usd')
                    ->label('Amount USD')
                    ->visible(Auth::user()?->super_admin),
            ]);
    }
}
