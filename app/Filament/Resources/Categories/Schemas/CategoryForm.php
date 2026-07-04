<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
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
                ->unique(Category::class, 'slug', ignoreRecord: true),
            Select::make('parent_id')
                ->label('Catégorie parente')
                ->relationship('parent', 'name')
                ->searchable()->preload()->nullable(),
            Textarea::make('description')->label('Description')->rows(3)->columnSpanFull(),
            TextInput::make('icon')->label('Icône')->maxLength(255),
            ColorPicker::make('color')->label('Couleur'),
            TextInput::make('sort_order')->label('Ordre')->numeric()->default(0),
            Toggle::make('is_active')->label('Actif')->default(true),
        ]);
    }
}