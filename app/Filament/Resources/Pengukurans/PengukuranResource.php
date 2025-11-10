<?php

namespace App\Filament\Resources\Pengukurans;

use App\Filament\Resources\Pengukurans\Pages\CreatePengukuran;
use App\Filament\Resources\Pengukurans\Pages\EditPengukuran;
use App\Filament\Resources\Pengukurans\Pages\ListPengukurans;
use App\Filament\Resources\Pengukurans\Pages\ViewPengukuran;
use App\Filament\Resources\Pengukurans\Schemas\PengukuranForm;
use App\Filament\Resources\Pengukurans\Schemas\PengukuranInfolist;
use App\Filament\Resources\Pengukurans\Tables\PengukuransTable;
use App\Models\Pengukuran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PengukuranResource extends Resource
{
    protected static ?string $model = Pengukuran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Pengukuran';

    public static function form(Schema $schema): Schema
    {
        return PengukuranForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PengukuranInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengukuransTable::configure($table);
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
            'index' => ListPengukurans::route('/'),
            'create' => CreatePengukuran::route('/create'),
            'view' => ViewPengukuran::route('/{record}'),
            'edit' => EditPengukuran::route('/{record}/edit'),
        ];
    }
}
