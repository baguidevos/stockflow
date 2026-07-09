<?php

namespace App\Filament\Resources\AttributeValues\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AttributeValuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('attribute.name')->label('Attribut')->sortable()->searchable(),
                TextColumn::make('value')->label('Valeur')->searchable()->sortable(),
                ColorColumn::make('color')->label('Couleur'),
                ImageColumn::make('image')->label('Image')->circular(),
                TextColumn::make('sort_order')->label('Ordre')->sortable(),
                IconColumn::make('is_active')->label('Actif')->boolean(),
            ])
            ->filters([
                SelectFilter::make('attribute_id')->label('Attribut')->relationship('attribute', 'name'),
                TernaryFilter::make('is_active')->label('Actif'),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}