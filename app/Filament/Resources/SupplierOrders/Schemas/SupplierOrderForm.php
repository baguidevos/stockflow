<?php

namespace App\Filament\Resources\SupplierOrders\Schemas;

use App\Models\SupplierOrder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SupplierOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Commande')->schema([
                TextInput::make('order_number')
                    ->label('N° de commande')
                    ->required()
                    ->unique(SupplierOrder::class, 'order_number', ignoreRecord: true)
                    ->default(fn () => 'CMD-' . strtoupper(uniqid())),
                Select::make('supplier_id')
                    ->label('Fournisseur')->relationship('supplier', 'name')->searchable()->preload()->required(),
                Select::make('warehouse_id')
                    ->label('Entrepôt destinataire')->relationship('warehouse', 'name')->searchable()->preload()->required(),
                Select::make('status')->label('Statut')
                    ->options([
                        'draft'     => 'Brouillon',
                        'sent'      => 'Envoyée',
                        'confirmed' => 'Confirmée',
                        'partial'   => 'Partiellement reçue',
                        'received'  => 'Reçue',
                        'cancelled' => 'Annulée',
                    ])->default('draft')->required(),
            ])->columns(2),

            Section::make('Dates')->schema([
                DatePicker::make('order_date')->label('Date de commande')->default(now())->required(),
                DatePicker::make('expected_date')->label('Livraison prévue'),
                DatePicker::make('received_date')->label('Date de réception'),
            ])->columns(3),

            Section::make('Montants')->schema([
                TextInput::make('subtotal')->label('Sous-total')->numeric()->prefix('FCFA')->default(0),
                TextInput::make('shipping_cost')->label('Frais de port')->numeric()->prefix('FCFA')->default(0),
                TextInput::make('tax_amount')->label('TVA')->numeric()->prefix('FCFA')->default(0),
                TextInput::make('total_amount')->label('Total TTC')->numeric()->prefix('FCFA')->default(0),
            ])->columns(2),

            Section::make('Notes')->schema([
                Textarea::make('notes')->label('Notes publiques')->rows(2),
                Textarea::make('internal_notes')->label('Notes internes')->rows(2),
            ])->columns(2),
        ]);
    }
}