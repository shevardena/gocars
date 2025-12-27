<?php

namespace App\Filament\Resources\CarModels;

use App\Filament\Resources\CarModels\Pages\CreateCarModel;
use App\Filament\Resources\CarModels\Pages\EditCarModel;
use App\Filament\Resources\CarModels\Pages\ListCarModels;
use App\Filament\Resources\CarModels\Pages\ViewCarModel;
use App\Filament\Resources\CarModels\RelationManagers\CarsRelationManager;
use App\Filament\Resources\CarModels\Schemas\CarModelForm;
use App\Filament\Resources\CarModels\Schemas\CarModelInfolist;
use App\Filament\Resources\CarModels\Tables\CarModelsTable;
use App\Models\CarModel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CarModelResource extends Resource
{
    protected static ?string $model = CarModel::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Cars';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CircleStack;

    protected static ?string $recordTitleAttribute = 'CarModel';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Models';

    public static function form(Schema $schema): Schema
    {
        return CarModelForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CarModelInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarModelsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CarsRelationManager::class
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('car_models.view');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCarModels::route('/'),
            'create' => CreateCarModel::route('/create'),
            'edit' => EditCarModel::route('/{record}/edit'),
        ];
    }
}
