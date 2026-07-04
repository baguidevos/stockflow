<?php

namespace App\Filament\Resources\Alerts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AlertForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('product_id')
                ->label('Produit')->relationship('product', 'name')->searchable()->preload()->required(),
            Select::make('warehouse_id')
                ->label('Entrepôt')->relationship('warehouse', 'name')->searchable()->preload(),
            Select::make('alert_type')->label("Type d'alerte")
                ->options([
                    'low_stock'      => 'Stock faible',
                    'out_of_stock'   => 'Rupture de stock',
                    'overstock'      => 'Surstock',
                    'expiring_soon'  => 'Expiration proche',
                    'expired'        => 'Expiré',
                    'price_change'   => 'Changement de prix',
                    'supplier_delay' => 'Délai fournisseur',
                ])->required(),
            TextInput::make('title')->label('Titre')->required()->maxLength(255)->columnSpanFull(),
            Textarea::make('message')->label('Message')->rows(3)->required()->columnSpanFull(),
            TextInput::make('current_quantity')->label('Quantité actuelle')->numeric(),
            TextInput::make('threshold_quantity')->label('Seuil')->numeric(),
            Toggle::make('is_read')->label('Lu')->default(false),
            Toggle::make('is_resolved')->label('Résolu')->default(false),
        ]);
    }
}