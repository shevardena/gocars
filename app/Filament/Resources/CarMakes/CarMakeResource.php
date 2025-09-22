<?php

namespace App\Filament\Resources\CarMakes;

use App\Filament\Resources\CarMakes\Pages\CreateCarMake;
use App\Filament\Resources\CarMakes\Pages\EditCarMake;
use App\Filament\Resources\CarMakes\Pages\ListCarMakes;
use App\Filament\Resources\CarMakes\Pages\ViewCarMake;
use App\Filament\Resources\CarMakes\RelationManagers\ModelsRelationManager;
use App\Filament\Resources\CarMakes\Schemas\CarMakeForm;
use App\Filament\Resources\CarMakes\Schemas\CarMakeInfolist;
use App\Filament\Resources\CarMakes\Tables\CarMakesTable;
use App\Models\CarMake;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CarMakeResource extends Resource
{
    protected static ?string $model = CarMake::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Cars';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ListBullet;

    protected static ?string $recordTitleAttribute = 'CarMake';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Make';

    public static function form(Schema $schema): Schema
    {
        return CarMakeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CarMakeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarMakesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ModelsRelationManager::class,
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('car_makes.view');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCarMakes::route('/'),
            'create' => CreateCarMake::route('/create'),
            'view' => ViewCarMake::route('/{record}'),
            'edit' => EditCarMake::route('/{record}/edit'),
        ];
    }
}
