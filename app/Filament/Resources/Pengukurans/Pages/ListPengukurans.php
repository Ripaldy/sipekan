<?php

namespace App\Filament\Resources\Pengukurans\Pages;

use App\Filament\Resources\Pengukurans\PengukuranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPengukurans extends ListRecords
{
    protected static string $resource = PengukuranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
