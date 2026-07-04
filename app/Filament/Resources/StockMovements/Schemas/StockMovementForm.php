<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('product_id')
                ->label('Produit')->relationship('product', 'name')->searchable()->preload()->required(),
            Select::make('warehouse_id')
                ->label('Entrepôt')->relationship('warehouse', 'name')->searchable()->preload()->required(),
            Select::make('movement_type')->label('Type de mouvement')
                ->options([
                    'purchase'           => 'Achat',
                    'sale'               => 'Vente',
                    'adjustment'         => 'Ajustement',
                    'transfer_in'        => 'Transfert entrant',
                    'transfer_out'       => 'Transfert sortant',
                    'return'             => 'Retour client',
                    'return_to_supplier' => 'Retour fournisseur',
                    'damage'             => 'Dommage / Perte',
                    'inventory'          => 'Inventaire',
                ])->required(),
            TextInput::make('quantity')->label('Quantité')->numeric()->required(),
            TextInput::make('unit_price')->label('Prix unitaire')->numeric()->prefix('FCFA')->default(0),
            TextInput::make('reference_number')->label('Référence')->maxLength(255),
            DateTimePicker::make('occurred_at')->label('Date du mouvement')->default(now()),
            Textarea::make('notes')->label('Notes')->rows(3)->columnSpanFull(),
        ]);
    }
}