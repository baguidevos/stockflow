<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Date')->dateTime('d/m/Y H:i:s')->sortable(),
                TextColumn::make('user.name')->label('Utilisateur')->searchable()->sortable(),
                BadgeColumn::make('action')->label('Action')
                    ->colors([
                        'success' => 'create',
                        'warning' => 'update',
                        'danger'  => 'delete',
                        'info'    => 'view',
                    ]),
                TextColumn::make('entity_type')->label('Entité')->searchable(),
                TextColumn::make('entity_id')->label('ID')->sortable(),
                TextColumn::make('description')->label('Description')->limit(60)->searchable(),
                TextColumn::make('ip_address')->label('IP'),
            ])
            ->filters([
                SelectFilter::make('action')->label('Action')
                    ->options([
                        'create' => 'Création',
                        'update' => 'Modification',
                        'delete' => 'Suppression',
                        'view'   => 'Consultation',
                    ]),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('Du'),
                        DatePicker::make('to')->label('Au'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['from'], fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                        ->when($data['to'],   fn ($q) => $q->whereDate('created_at', '<=', $data['to']))
                    ),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([ViewAction::make()])
            ->bulkActions([]);
    }
}