<?php

namespace App\Filament\Resources\Alerts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AlertsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Date')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('product.name')->label('Produit')->searchable()->sortable(),
                BadgeColumn::make('alert_type')->label('Type')
                    ->colors([
                        'warning' => fn ($state) => in_array($state, ['low_stock', 'expiring_soon']),
                        'danger'  => fn ($state) => in_array($state, ['out_of_stock', 'expired']),
                        'info'    => fn ($state) => in_array($state, ['overstock', 'price_change', 'supplier_delay']),
                    ]),
                TextColumn::make('title')->label('Titre')->searchable()->limit(50),
                TextColumn::make('current_quantity')->label('Qté actuelle'),
                TextColumn::make('threshold_quantity')->label('Seuil'),
                IconColumn::make('is_read')->label('Lu')->boolean(),
                IconColumn::make('is_resolved')->label('Résolu')->boolean(),
            ])
            ->filters([
                SelectFilter::make('alert_type')->label("Type d'alerte")
                    ->options([
                        'low_stock'      => 'Stock faible',
                        'out_of_stock'   => 'Rupture',
                        'overstock'      => 'Surstock',
                        'expiring_soon'  => 'Expiration proche',
                        'expired'        => 'Expiré',
                        'price_change'   => 'Prix',
                        'supplier_delay' => 'Délai',
                    ]),
                TernaryFilter::make('is_read')->label('Lu'),
                TernaryFilter::make('is_resolved')->label('Résolu'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}