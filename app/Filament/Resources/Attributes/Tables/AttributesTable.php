<?php

namespace App\Filament\Resources\Attributes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AttributesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group.name')->label('Groupe')->sortable()->searchable(),
                TextColumn::make('name')->label('Nom')->searchable()->sortable(),
                TextColumn::make('type')->label('Type')
                ->badge()
                    ->colors([
                        'primary' => 'select',
                        'warning' => 'color',
                        'success' => 'text',
                        'danger'  => 'number',
                    ]),
                ColorColumn::make('color')->label('Couleur'),
                IconColumn::make('is_filterable')->label('Filtrable')->boolean(),
                IconColumn::make('is_required')->label('Obligatoire')->boolean(),
                IconColumn::make('is_active')->label('Actif')->boolean(),
            ])
            ->filters([
                SelectFilter::make('attribute_group_id')
                    ->label('Groupe')->relationship('group', 'name'),
                TernaryFilter::make('is_active')->label('Actif'),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}