<?php

namespace App\Filament\Resources\VitaminObats\Pages;

use App\Filament\Resources\VitaminObats\VitaminObatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVitaminObats extends ListRecords
{
    protected static string $resource = VitaminObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
