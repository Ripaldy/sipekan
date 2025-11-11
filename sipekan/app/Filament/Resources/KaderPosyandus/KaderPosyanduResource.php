<?php

namespace App\Filament\Resources\KaderPosyandus;

use App\Filament\Resources\KaderPosyandus\Pages\CreateKaderPosyandu;
use App\Filament\Resources\KaderPosyandus\Pages\EditKaderPosyandu;
use App\Filament\Resources\KaderPosyandus\Pages\ListKaderPosyandus;
use App\Filament\Resources\KaderPosyandus\Pages\ViewKaderPosyandu;
use App\Filament\Resources\KaderPosyandus\Schemas\KaderPosyanduForm;
use App\Filament\Resources\KaderPosyandus\Schemas\KaderPosyanduInfolist;
use App\Filament\Resources\KaderPosyandus\Tables\KaderPosyandusTable;
use App\Models\KaderPosyandu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KaderPosyanduResource extends Resource
{
    protected static ?string $model = KaderPosyandu::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Kader Posyandu';

    public static function form(Schema $schema): Schema
    {
        return KaderPosyanduForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KaderPosyanduInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KaderPosyandusTable::configure($table);
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
            'index' => ListKaderPosyandus::route('/'),
            'create' => CreateKaderPosyandu::route('/create'),
            'view' => ViewKaderPosyandu::route('/{record}'),
            'edit' => EditKaderPosyandu::route('/{record}/edit'),
        ];
    }
}
