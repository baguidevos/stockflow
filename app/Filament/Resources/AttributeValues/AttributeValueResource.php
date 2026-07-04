<?php

namespace App\Filament\Resources\AttributeValues;

use App\Filament\Resources\AttributeValues\Pages;
use App\Filament\Resources\AttributeValues\Schemas\AttributeValueForm;
use App\Filament\Resources\AttributeValues\Tables\AttributeValuesTable;
use App\Models\AttributeValue;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AttributeValueResource extends Resource
{
    protected static ?string $model = AttributeValue::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-swatch';
    protected static \UnitEnum|string|null $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 22;
    protected static ?string $modelLabel = "Valeur d'attribut";
    protected static ?string $pluralModelLabel = "Valeurs d'attributs";

    public static function form(Schema $schema): Schema
    {
        return AttributeValueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributeValuesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAttributeValues::route('/'),
            'create' => Pages\CreateAttributeValue::route('/create'),
            'edit'   => Pages\EditAttributeValue::route('/{record}/edit'),
        ];
    }
}