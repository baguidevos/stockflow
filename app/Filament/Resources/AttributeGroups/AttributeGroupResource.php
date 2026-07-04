<?php

namespace App\Filament\Resources\AttributeGroups;

use App\Filament\Resources\AttributeGroups\Pages;
use App\Filament\Resources\AttributeGroups\Schemas\AttributeGroupForm;
use App\Filament\Resources\AttributeGroups\Tables\AttributeGroupsTable;
use App\Models\AttributeGroup;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AttributeGroupResource extends Resource
{
    protected static ?string $model = AttributeGroup::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-squares-2x2';
    protected static \UnitEnum|string|null $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 20;
    protected static ?string $modelLabel = "Groupe d'attributs";
    protected static ?string $pluralModelLabel = "Groupes d'attributs";

    public static function form(Schema $schema): Schema
    {
        return AttributeGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributeGroupsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAttributeGroups::route('/'),
            'create' => Pages\CreateAttributeGroup::route('/create'),
            'edit'   => Pages\EditAttributeGroup::route('/{record}/edit'),
        ];
    }
}