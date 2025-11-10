<?php

namespace App\Filament\Resources\KaderPosyandus\Pages;

use App\Filament\Resources\KaderPosyandus\KaderPosyanduResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKaderPosyandu extends EditRecord
{
    protected static string $resource = KaderPosyanduResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
