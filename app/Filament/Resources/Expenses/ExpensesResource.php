<?php

namespace App\Filament\Resources\Expenses;

use App\Filament\Resources\Expenses\Pages\CreateExpenses;
use App\Filament\Resources\Expenses\Pages\EditExpenses;
use App\Filament\Resources\Expenses\Pages\ListExpenses;
use App\Filament\Resources\Expenses\Pages\ViewExpenses;
use App\Filament\Resources\Expenses\Schemas\ExpensesForm;
use App\Filament\Resources\Expenses\Schemas\ExpensesInfolist;
use App\Filament\Resources\Expenses\Tables\ExpensesTable;
use App\Models\Expense;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpensesResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Administration';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Expense';

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationLabel = 'Expenses';

    public static function form(Schema $schema): Schema
    {
        return ExpensesForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ExpensesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpensesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('expenses.view');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpenses::route('/'),
            'create' => CreateExpenses::route('/create'),
            'view' => ViewExpenses::route('/{record}'),
            'edit' => EditExpenses::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
