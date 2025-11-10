<?php

namespace App\Filament\Resources\Pengukurans\Pages;

use App\Filament\Resources\Pengukurans\PengukuranResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPengukuran extends ViewRecord
{
    protected static string $resource = PengukuranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
