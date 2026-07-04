<?php

namespace App\Filament\Resources\ProductVariants\Schemas;

use App\Models\ProductVariant;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductVariantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('product_id')
                ->label('Produit parent')
                ->relationship('product', 'name')
                ->searchable()->preload()->required(),
            TextInput::make('name')->label('Nom de la variante')->required()->maxLength(255),
            TextInput::make('sku')->label('SKU')->required()
                ->unique(ProductVariant::class, 'sku', ignoreRecord: true),
            TextInput::make('barcode')->label('Code-barres')
                ->unique(ProductVariant::class, 'barcode', ignoreRecord: true),
            TextInput::make('selling_price')->label('Prix de vente')->numeric()->prefix('FCFA'),
            TextInput::make('purchase_price')->label("Prix d'achat")->numeric()->prefix('FCFA'),
            TextInput::make('price_adjustment')->label('Ajustement prix')->numeric()->default(0),
            TextInput::make('stock_quantity')->label('Stock')->numeric()->default(0),
            TextInput::make('min_stock_level')->label('Seuil min')->numeric()->default(0),
            Select::make('stock_status')->label('Statut stock')
                ->options(['in_stock' => 'En stock', 'low_stock' => 'Stock faible', 'out_of_stock' => 'Rupture'])
                ->default('in_stock'),
            FileUpload::make('image')->label('Image')->image()->directory('products/variants'),
            Toggle::make('is_default')->label('Variante par défaut')->default(false),
            Toggle::make('is_active')->label('Actif')->default(true),
        ]);
    }
}