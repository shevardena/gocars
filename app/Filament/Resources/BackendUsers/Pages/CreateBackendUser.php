<?php

namespace App\Filament\Resources\BackendUsers\Pages;

use App\Filament\Resources\BackendUsers\BackendUserResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateBackendUser extends CreateRecord
{
    protected static string $resource = BackendUserResource::class;

    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->submit('create')
                ->color('primary'),

            Action::make('cancel')
                ->url($this->getResource()::getUrl())
                ->color('gray'),
        ];
    }
}
