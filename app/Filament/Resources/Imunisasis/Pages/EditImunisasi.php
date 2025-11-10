<?php

namespace App\Filament\Resources\Imunisasis\Pages;

use App\Filament\Resources\Imunisasis\ImunisasiResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditImunisasi extends EditRecord
{
    protected static string $resource = ImunisasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
