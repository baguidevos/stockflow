<?php

namespace App\Filament\Resources\ProductVariants\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductVariantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('Image')->circular(),
                TextColumn::make('product.name')->label('Produit')->searchable()->sortable(),
                TextColumn::make('name')->label('Variante')->searchable()->sortable(),
                TextColumn::make('sku')->label('SKU')->searchable(),
                TextColumn::make('selling_price')->label('Prix vente')->money('XOF'),
                TextColumn::make('stock_quantity')->label('Stock')->sortable(),
                BadgeColumn::make('stock_status')->label('Statut')
                    ->colors(['success' => 'in_stock', 'warning' => 'low_stock', 'danger' => 'out_of_stock']),
                IconColumn::make('is_default')->label('Défaut')->boolean(),
                IconColumn::make('is_active')->label('Actif')->boolean(),
            ])
            ->filters([
                SelectFilter::make('product_id')->label('Produit')->relationship('product', 'name'),
                SelectFilter::make('stock_status')->label('Statut stock')
                    ->options(['in_stock' => 'En stock', 'low_stock' => 'Stock faible', 'out_of_stock' => 'Rupture']),
                TernaryFilter::make('is_active')->label('Actif'),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}