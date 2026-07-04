<?php

namespace App\Filament\Resources\Attributes;

use App\Filament\Resources\Attributes\Pages;
use App\Filament\Resources\Attributes\Schemas\AttributeForm;
use App\Filament\Resources\Attributes\Tables\AttributesTable;
use App\Models\Attribute;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-list-bullet';
    protected static \UnitEnum|string|null $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 21;
    protected static ?string $modelLabel = 'Attribut';
    protected static ?string $pluralModelLabel = 'Attributs';

    public static function form(Schema $schema): Schema
    {
        return AttributeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAttributes::route('/'),
            'create' => Pages\CreateAttribute::route('/create'),
            'edit'   => Pages\EditAttribute::route('/{record}/edit'),
        ];
    }
}