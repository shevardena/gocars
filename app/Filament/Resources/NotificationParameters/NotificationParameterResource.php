<?php

namespace App\Filament\Resources\NotificationParameters;

use App\Filament\Resources\NotificationParameters\Pages\CreateNotificationParameter;
use App\Filament\Resources\NotificationParameters\Pages\EditNotificationParameter;
use App\Filament\Resources\NotificationParameters\Pages\ListNotificationParameters;
use App\Filament\Resources\NotificationParameters\Schemas\NotificationParameterForm;
use App\Filament\Resources\NotificationParameters\Tables\NotificationParametersTable;
use App\Models\NotificationParameter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NotificationParameterResource extends Resource
{
    protected static ?string $model = NotificationParameter::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Administration';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BellAlert;

    protected static ?string $recordTitleAttribute = 'NotificationParameter';

    protected static ?int $navigationSort = 11;

    public static function form(Schema $schema): Schema
    {
        return NotificationParameterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NotificationParametersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNotificationParameters::route('/'),
            'create' => CreateNotificationParameter::route('/create'),
            'edit' => EditNotificationParameter::route('/{record}/edit'),
        ];
    }
}
