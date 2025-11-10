<?php

namespace App\Filament\Resources\KaderPosyandus\Pages;

use App\Filament\Resources\KaderPosyandus\KaderPosyanduResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKaderPosyandu extends ViewRecord
{
    protected static string $resource = KaderPosyanduResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
