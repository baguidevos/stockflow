<?php

namespace App\Filament\Resources\Brands\Schemas;

use App\Models\Brand;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations générales')->schema([
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
                    ->unique(Brand::class, 'slug', ignoreRecord: true),
                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->columnSpanFull(),
            ])->columns(2),

            Section::make('Contact & localisation')->schema([
                TextInput::make('website')->label('Site web')->url(),
                TextInput::make('country')->label('Pays')->maxLength(100),
                TextInput::make('contact_email')->label('Email')->email(),
                TextInput::make('contact_phone')->label('Téléphone')->tel(),
            ])->columns(2),

            Section::make('Paramètres')->schema([
                FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->directory('brands/logos')
                    ->columnSpanFull(),
                Textarea::make('notes')->label('Notes')->rows(2)->columnSpanFull(),
                TextInput::make('sort_order')->label("Ordre d'affichage")->numeric()->default(0),
                Toggle::make('is_active')->label('Actif')->default(true),
            ])->columns(2),
        ]);
    }
}