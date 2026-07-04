<?php

namespace App\Filament\Resources\AttributeGroups\Schemas;

use App\Models\AttributeGroup;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AttributeGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
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
                ->unique(AttributeGroup::class, 'slug', ignoreRecord: true),
            TextInput::make('display_name')->label('Nom affiché')->maxLength(255),
            TextInput::make('description')->label('Description')->maxLength(255),
            TextInput::make('sort_order')->label('Ordre')->numeric()->default(0),
            Toggle::make('is_active')->label('Actif')->default(true),
        ]);
    }
}