<?php

namespace App\Filament\Resources\BalanceHistories\Pages;

use App\Filament\Resources\BalanceHistories\BalanceHistoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBalanceHistories extends ListRecords
{
    protected static string $resource = BalanceHistoryResource::class;
}
