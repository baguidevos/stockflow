<?php

namespace App\Filament\Resources\StockMovements\Tables;

use Filament\Forms\Components\DatePicker;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('occurred_at')->label('Date')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('product.name')->label('Produit')->searchable()->sortable(),
                TextColumn::make('warehouse.name')->label('Entrepôt')->sortable(),
                BadgeColumn::make('movement_type')->label('Type')
                    ->colors([
                        'success' => fn ($state) => in_array($state, ['purchase', 'return', 'transfer_in', 'inventory']),
                        'danger'  => fn ($state) => in_array($state, ['sale', 'transfer_out', 'damage']),
                        'warning' => 'adjustment',
                    ]),
                TextColumn::make('quantity')->label('Quantité')->sortable(),
                TextColumn::make('quantity_before')->label('Avant'),
                TextColumn::make('quantity_after')->label('Après'),
                TextColumn::make('reference_number')->label('Référence')->searchable(),
                TextColumn::make('createdBy.name')->label('Par'),
            ])
            ->filters([
                SelectFilter::make('warehouse_id')
                    ->label('Entrepôt')->relationship('warehouse', 'name'),
                SelectFilter::make('movement_type')->label('Type')
                    ->options([
                        'purchase'           => 'Achat',
                        'sale'               => 'Vente',
                        'adjustment'         => 'Ajustement',
                        'transfer_in'        => 'Transfert entrant',
                        'transfer_out'       => 'Transfert sortant',
                        'return'             => 'Retour client',
                        'return_to_supplier' => 'Retour fournisseur',
                        'damage'             => 'Dommage',
                        'inventory'          => 'Inventaire',
                    ]),
                Filter::make('occurred_at')
                    ->form([
                        DatePicker::make('from')->label('Du'),
                        DatePicker::make('to')->label('Au'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['from'], fn ($q) => $q->whereDate('occurred_at', '>=', $data['from']))
                        ->when($data['to'],   fn ($q) => $q->whereDate('occurred_at', '<=', $data['to']))
                    ),
            ])
            ->defaultSort('occurred_at', 'desc')
            ->actions([ViewAction::make()])
            ->bulkActions([]);
    }
}