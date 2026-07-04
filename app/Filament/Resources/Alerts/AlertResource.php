<?php

namespace App\Filament\Resources\Alerts;

use App\Filament\Resources\Alerts\Pages;
use App\Filament\Resources\Alerts\Schemas\AlertForm;
use App\Filament\Resources\Alerts\Tables\AlertsTable;
use App\Models\Alert;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AlertResource extends Resource
{
    protected static ?string $model = Alert::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-bell-alert';
    protected static \UnitEnum|string|null $navigationGroup = 'Stock';
    protected static ?int $navigationSort = 32;
    protected static ?string $modelLabel = 'Alerte';
    protected static ?string $pluralModelLabel = 'Alertes';

    public static function form(Schema $schema): Schema
    {
        return AlertForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AlertsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAlerts::route('/'),
            'create' => Pages\CreateAlert::route('/create'),
            'edit'   => Pages\EditAlert::route('/{record}/edit'),
        ];
    }
}