<?php

namespace App\Filament\Resources\SupplierOrders;

use App\Filament\Resources\SupplierOrders\Pages;
use App\Filament\Resources\SupplierOrders\Schemas\SupplierOrderForm;
use App\Filament\Resources\SupplierOrders\Tables\SupplierOrdersTable;
use App\Models\SupplierOrder;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SupplierOrderResource extends Resource
{
    protected static ?string $model = SupplierOrder::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-shopping-cart';
    protected static \UnitEnum|string|null $navigationGroup = 'Achats';
    protected static ?int $navigationSort = 41;
    protected static ?string $modelLabel = 'Commande fournisseur';
    protected static ?string $pluralModelLabel = 'Commandes fournisseurs';

    public static function form(Schema $schema): Schema
    {
        return SupplierOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupplierOrdersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSupplierOrders::route('/'),
            'create' => Pages\CreateSupplierOrder::route('/create'),
            'edit'   => Pages\EditSupplierOrder::route('/{record}/edit'),
        ];
    }
}