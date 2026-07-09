<?php

namespace App\Filament\Resources\AttributeValues\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AttributeValueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('attribute_id')
                ->label('Attribut')
                ->relationship('attribute', 'name')
                ->searchable()->preload()->required(),
            TextInput::make('value')
                ->label('Valeur')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                    $operation === 'create' ? $set('slug', Str::slug($state)) : null
                ),
            TextInput::make('slug')->label('Slug')->required()->maxLength(255),
            ColorPicker::make('color')->label('Couleur'),
            FileUpload::make('image')->label('Image')->image()->directory('attributes/values'),
            TextInput::make('sort_order')->label('Ordre')->numeric()->default(0),
            Toggle::make('is_active')->label('Actif')->default(true),
        ]);
    }
}