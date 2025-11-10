<?php

namespace App\Filament\Resources\VitaminObats;

use App\Filament\Resources\VitaminObats\Pages\CreateVitaminObat;
use App\Filament\Resources\VitaminObats\Pages\EditVitaminObat;
use App\Filament\Resources\VitaminObats\Pages\ListVitaminObats;
use App\Filament\Resources\VitaminObats\Pages\ViewVitaminObat;
use App\Filament\Resources\VitaminObats\Schemas\VitaminObatForm;
use App\Filament\Resources\VitaminObats\Schemas\VitaminObatInfolist;
use App\Filament\Resources\VitaminObats\Tables\VitaminObatsTable;
use App\Models\VitaminObat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VitaminObatResource extends Resource
{
    protected static ?string $model = VitaminObat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Vitamin dan Obat';

    public static function form(Schema $schema): Schema
    {
        return VitaminObatForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VitaminObatInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VitaminObatsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVitaminObats::route('/'),
            'create' => CreateVitaminObat::route('/create'),
            'view' => ViewVitaminObat::route('/{record}'),
            'edit' => EditVitaminObat::route('/{record}/edit'),
        ];
    }
}
