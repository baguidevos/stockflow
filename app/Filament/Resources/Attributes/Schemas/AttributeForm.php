<?php

namespace App\Filament\Resources\Attributes\Schemas;

use App\Models\Attribute;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('attribute_group_id')
                ->label("Groupe d'attributs")
                ->relationship('attributeGroup', 'name')
                ->searchable()->preload()->required(),
            TextInput::make('name')
                ->label('Nom')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                    $operation === 'create' ? $set('slug', Str::slug($state)) : null
                ),
            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->unique(Attribute::class, 'slug', ignoreRecord: true),
            Select::make('type')
                ->label('Type')
                ->options([
                    'select'  => 'Sélection',
                    'color'   => 'Couleur',
                    'text'    => 'Texte',
                    'number'  => 'Nombre',
                    'boolean' => 'Oui/Non',
                ])
                ->default('select')->required(),
            ColorPicker::make('color')->label('Couleur'),
            TextInput::make('value')->label('Valeur par défaut')->maxLength(255),
            TextInput::make('sort_order')->label('Ordre')->numeric()->default(0),
            Toggle::make('is_filterable')->label('Filtrable')->default(true),
            Toggle::make('is_required')->label('Obligatoire')->default(false),
            Toggle::make('is_active')->label('Actif')->default(true),
        ]);
    }
}