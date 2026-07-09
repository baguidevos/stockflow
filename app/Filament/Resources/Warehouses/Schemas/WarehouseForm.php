<?php

namespace App\Filament\Resources\Warehouses\Schemas;

use App\Models\Warehouse;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class WarehouseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations')->schema([
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
                    ->unique(Warehouse::class, 'slug', ignoreRecord: true),
                Textarea::make('description')->label('Description')->rows(2)->columnSpanFull(),
            ])->columns(2),

            Section::make('Localisation')->schema([
                Textarea::make('address')->label('Adresse')->rows(2)->columnSpanFull(),
                TextInput::make('city')->label('Ville')->maxLength(100),
                TextInput::make('postal_code')->label('Code postal')->maxLength(20),
                TextInput::make('country')->label('Pays')->default('Togo')->maxLength(100),
            ])->columns(2),

            Section::make('Paramètres')->schema([
                Toggle::make('is_default')->label('Entrepôt par défaut')->default(false),
                Toggle::make('is_active')->label('Actif')->default(true),
            ])->columns(2),
        ]);
    }
}