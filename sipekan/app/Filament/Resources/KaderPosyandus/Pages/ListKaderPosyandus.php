<?php

namespace App\Filament\Resources\KaderPosyandus\Pages;

use App\Filament\Resources\KaderPosyandus\KaderPosyanduResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKaderPosyandus extends ListRecords
{
    protected static string $resource = KaderPosyanduResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
