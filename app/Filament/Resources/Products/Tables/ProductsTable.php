<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')->label('SKU')->searchable()->sortable(),
                TextColumn::make('name')->label('Nom')->searchable()->sortable()->wrap(),
                TextColumn::make('category.name')->label('Catégorie')->sortable(),
                TextColumn::make('brand.name')->label('Marque')->sortable(),
                TextColumn::make('selling_price')->label('Prix vente')->money('XOF')->sortable(),
                TextColumn::make('stock_quantity')->label('Stock')->sortable(),
                BadgeColumn::make('stock_status')->label('Statut stock')
                    ->colors([
                        'success' => 'in_stock',
                        'warning' => 'low_stock',
                        'danger'  => 'out_of_stock',
                    ]),
                IconColumn::make('is_active')->label('Actif')->boolean(),
            ])
            ->filters([
                SelectFilter::make('category_id')->label('Catégorie')->relationship('category', 'name'),
                SelectFilter::make('brand_id')->label('Marque')->relationship('brand', 'name'),
                SelectFilter::make('stock_status')->label('Statut stock')
                    ->options(['in_stock' => 'En stock', 'low_stock' => 'Stock faible', 'out_of_stock' => 'Rupture']),
                TernaryFilter::make('is_active')->label('Actif'),
                TrashedFilter::make(),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([
                DeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
            ])]);
    }
}