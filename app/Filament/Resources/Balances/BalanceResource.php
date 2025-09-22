<?php

namespace App\Filament\Resources\Balances;

use App\Filament\Resources\Balances\Pages\CreateBalance;
use App\Filament\Resources\Balances\Pages\EditBalance;
use App\Filament\Resources\Balances\Pages\ListBalances;
use App\Filament\Resources\Balances\Pages\ViewBalance;
use App\Filament\Resources\Balances\Schemas\BalanceForm;
use App\Filament\Resources\Balances\Schemas\BalanceInfolist;
use App\Filament\Resources\Balances\Tables\BalancesTable;
use App\Models\Balance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BalanceResource extends Resource
{
    protected static ?string $model = Balance::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Administration';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CurrencyDollar;

    protected static ?string $recordTitleAttribute = 'Balance';

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationLabel = 'Balance';


    public static function form(Schema $schema): Schema
    {
        return BalanceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BalanceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BalancesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('balance.view');
    }


    public static function getPages(): array
    {
        return [
            'index' => ListBalances::route('/'),
            'create' => CreateBalance::route('/create'),
            'view' => ViewBalance::route('/{record}'),
            'edit' => EditBalance::route('/{record}/edit'),
        ];
    }
}
