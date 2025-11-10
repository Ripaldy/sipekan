<?php

namespace App\Filament\Resources\Imunisasis;

use App\Filament\Resources\Imunisasis\Pages\CreateImunisasi;
use App\Filament\Resources\Imunisasis\Pages\EditImunisasi;
use App\Filament\Resources\Imunisasis\Pages\ListImunisasis;
use App\Filament\Resources\Imunisasis\Pages\ViewImunisasi;
use App\Filament\Resources\Imunisasis\Schemas\ImunisasiForm;
use App\Filament\Resources\Imunisasis\Schemas\ImunisasiInfolist;
use App\Filament\Resources\Imunisasis\Tables\ImunisasisTable;
use App\Models\Imunisasi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ImunisasiResource extends Resource
{
    protected static ?string $model = Imunisasi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Imunisasi';

    public static function form(Schema $schema): Schema
    {
        return ImunisasiForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ImunisasiInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ImunisasisTable::configure($table);
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
            'index' => ListImunisasis::route('/'),
            'create' => CreateImunisasi::route('/create'),
            'view' => ViewImunisasi::route('/{record}'),
            'edit' => EditImunisasi::route('/{record}/edit'),
        ];
    }
}
