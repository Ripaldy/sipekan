<?php

namespace App\Filament\Resources\Balitas;

use App\Filament\Resources\Balitas\Pages\CreateBalita;
use App\Filament\Resources\Balitas\Pages\EditBalita;
use App\Filament\Resources\Balitas\Pages\ListBalitas;
use App\Filament\Resources\Balitas\Pages\ViewBalita;
use App\Filament\Resources\Balitas\Schemas\BalitaForm;
use App\Filament\Resources\Balitas\Schemas\BalitaInfolist;
use App\Filament\Resources\Balitas\Tables\BalitasTable;
use App\Models\Balita;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BalitaResource extends Resource
{
    protected static ?string $model = Balita::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Balita';

    public static function form(Schema $schema): Schema
    {
        return BalitaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BalitaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BalitasTable::configure($table);
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
            'index' => ListBalitas::route('/'),
            'create' => CreateBalita::route('/create'),
            'view' => ViewBalita::route('/{record}'),
            'edit' => EditBalita::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
