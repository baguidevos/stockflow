<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identification')->schema([
                TextInput::make('name')
                    ->label('Nom du produit')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                    )
                    ->columnSpanFull(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->unique(Product::class, 'sku', ignoreRecord: true),
                TextInput::make('barcode')
                    ->label('Code-barres')
                    ->unique(Product::class, 'barcode', ignoreRecord: true),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(Product::class, 'slug', ignoreRecord: true)
                    ->columnSpanFull(),
                Select::make('category_id')
                    ->label('Catégorie')
                    ->relationship('category', 'name')
                    ->searchable()->preload(),
                Select::make('brand_id')
                    ->label('Marque')
                    ->relationship('brand', 'name')
                    ->searchable()->preload(),
            ])->columns(2),

            Section::make('Description')->schema([
                Textarea::make('description')->label('Description')->rows(4)->columnSpanFull(),
                Textarea::make('specifications')->label('Spécifications techniques')->rows(3)->columnSpanFull(),
            ]),

            Section::make('Tarification')->schema([
                TextInput::make('purchase_price')->label("Prix d'achat")->numeric()->prefix('FCFA')->default(0),
                TextInput::make('selling_price')->label('Prix de vente HT')->numeric()->prefix('FCFA')->default(0),
                TextInput::make('tax_rate')->label('Taux TVA (%)')->numeric()->suffix('%')->default(18),
                TextInput::make('selling_price_with_tax')->label('Prix TTC')->numeric()->prefix('FCFA')->default(0),
            ])->columns(2),

            Section::make('Stock')->schema([
                TextInput::make('stock_quantity')->label('Quantité en stock')->numeric()->default(0),
                TextInput::make('min_stock_level')->label('Seuil min')->numeric()->default(0),
                TextInput::make('max_stock_level')->label('Seuil max')->numeric()->default(0),
                TextInput::make('unit')->label('Unité')->default('unité'),
                TextInput::make('weight')->label('Poids')->maxLength(20),
                TextInput::make('dimensions')->label('Dimensions')->maxLength(50),
            ])->columns(2),

            Section::make('Paramètres')->schema([
                Select::make('type')
                    ->label('Type de produit')
                    ->options(['simple' => 'Simple', 'configurable' => 'Configurable'])
                    ->default('simple')->required(),
                Select::make('stock_status')
                    ->label('Statut stock')
                    ->options([
                        'in_stock'     => 'En stock',
                        'low_stock'    => 'Stock faible',
                        'out_of_stock' => 'Rupture de stock',
                    ])->default('in_stock'),
                Toggle::make('is_active')->label('Actif')->default(true),
                Toggle::make('track_inventory')->label('Suivre les stocks')->default(true),
                Toggle::make('has_variants')->label('A des variantes')->default(false),
            ])->columns(2),
        ]);
    }
}