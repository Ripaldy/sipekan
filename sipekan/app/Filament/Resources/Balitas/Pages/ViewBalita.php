<?php

namespace App\Filament\Resources\Balitas\Pages;

use App\Filament\Resources\Balitas\BalitaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBalita extends ViewRecord
{
    protected static string $resource = BalitaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
