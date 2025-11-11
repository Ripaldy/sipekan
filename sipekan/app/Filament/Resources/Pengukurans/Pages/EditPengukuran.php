<?php

namespace App\Filament\Resources\Pengukurans\Pages;

use App\Filament\Resources\Pengukurans\PengukuranResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPengukuran extends EditRecord
{
    protected static string $resource = PengukuranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
