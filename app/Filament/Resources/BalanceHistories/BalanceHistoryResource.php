<?php

namespace App\Filament\Resources\BalanceHistories;

use App\Filament\Resources\BalanceHistories\Pages\CreateBalanceHistory;
use App\Filament\Resources\BalanceHistories\Pages\EditBalanceHistory;
use App\Filament\Resources\BalanceHistories\Pages\ListBalanceHistories;
use App\Filament\Resources\BalanceHistories\Pages\ViewBalanceHistory;
use App\Filament\Resources\BalanceHistories\Schemas\BalanceHistoryForm;
use App\Filament\Resources\BalanceHistories\Schemas\BalanceHistoryInfolist;
use App\Filament\Resources\BalanceHistories\Tables\BalanceHistoriesTable;
use App\Models\BalanceHistory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BalanceHistoryResource extends Resource
{
    protected static ?string $model = BalanceHistory::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Administration';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    protected static ?string $recordTitleAttribute = 'Balance History';

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationLabel = 'Balance History';

    public static function form(Schema $schema): Schema
    {
        return BalanceHistoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BalanceHistoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BalanceHistoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('balance_histories.view');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBalanceHistories::route('/'),
        ];
    }
}
