<?php

namespace App\Filament\Resources\Imunisasis\Pages;

use App\Filament\Resources\Imunisasis\ImunisasiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListImunisasis extends ListRecords
{
    protected static string $resource = ImunisasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
