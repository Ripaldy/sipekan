<?php

namespace App\Filament\Resources\Imunisasis\Pages;

use App\Filament\Resources\Imunisasis\ImunisasiResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewImunisasi extends ViewRecord
{
    protected static string $resource = ImunisasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
