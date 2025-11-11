<?php

namespace App\Filament\Resources\VitaminObats\Pages;

use App\Filament\Resources\VitaminObats\VitaminObatResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditVitaminObat extends EditRecord
{
    protected static string $resource = VitaminObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
