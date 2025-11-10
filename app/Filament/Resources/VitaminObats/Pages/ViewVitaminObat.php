<?php

namespace App\Filament\Resources\VitaminObats\Pages;

use App\Filament\Resources\VitaminObats\VitaminObatResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVitaminObat extends ViewRecord
{
    protected static string $resource = VitaminObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
