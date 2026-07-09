<?php

namespace App\Filament\Resources\SupplierOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SupplierOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')->label('N° Commande')->searchable()->sortable(),
                TextColumn::make('supplier.name')->label('Fournisseur')->searchable()->sortable(),
                TextColumn::make('warehouse.name')->label('Entrepôt')->sortable(),
                TextColumn::make('order_date')->label('Date')->date('d/m/Y')->sortable(),
                TextColumn::make('expected_date')->label('Livraison prévue')->date('d/m/Y'),
                BadgeColumn::make('status')->label('Statut')
                    ->colors([
                        'gray'    => 'draft',
                        'info'    => 'sent',
                        'warning' => fn ($state) => in_array($state, ['confirmed', 'partial']),
                        'success' => 'received',
                        'danger'  => 'cancelled',
                    ]),
                TextColumn::make('total_amount')->label('Total')->money('XOF')->sortable(),
            ])
            ->filters([
                SelectFilter::make('supplier_id')->label('Fournisseur')->relationship('supplier', 'name'),
                SelectFilter::make('status')->label('Statut')
                    ->options([
                        'draft'     => 'Brouillon',
                        'sent'      => 'Envoyée',
                        'confirmed' => 'Confirmée',
                        'partial'   => 'Partielle',
                        'received'  => 'Reçue',
                        'cancelled' => 'Annulée',
                    ]),
            ])
            ->defaultSort('order_date', 'desc')
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}