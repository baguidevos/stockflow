<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use App\Models\Supplier;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations principales')->schema([
                TextInput::make('name')
                    ->label('Raison sociale')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                    ),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(Supplier::class, 'slug', ignoreRecord: true),
                TextInput::make('contact_person')->label('Personne de contact')->maxLength(255),
                TextInput::make('email')->label('Email')->email()->required()
                    ->unique(Supplier::class, 'email', ignoreRecord: true),
                TextInput::make('phone')->label('Téléphone')->tel()->maxLength(30),
                TextInput::make('mobile')->label('Mobile')->tel()->maxLength(30),
            ])->columns(2),

            Section::make('Adresse')->schema([
                Textarea::make('address')->label('Adresse')->rows(2)->columnSpanFull(),
                TextInput::make('city')->label('Ville')->maxLength(100),
                TextInput::make('postal_code')->label('Code postal')->maxLength(20),
                TextInput::make('country')->label('Pays')->default('Togo')->maxLength(100),
            ])->columns(2),

            Section::make('Informations légales')->schema([
                TextInput::make('siret')->label('SIRET / IFU')->maxLength(20),
                TextInput::make('tva_intracom')->label('TVA Intracom')->maxLength(20),
                TextInput::make('rating')->label('Note (0-5)')->numeric()->minValue(0)->maxValue(5)->step(0.1),
                Textarea::make('notes')->label('Notes internes')->rows(3),
            ])->columns(2),

            Toggle::make('is_active')->label('Actif')->default(true),
        ]);
    }
}