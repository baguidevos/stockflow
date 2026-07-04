<?php

namespace App\Filament\Resources\ActivityLogs\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ActivityLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('action')->label('Action')->disabled(),
            TextInput::make('entity_type')->label('Entité')->disabled(),
            TextInput::make('entity_id')->label('ID Entité')->disabled(),
            TextInput::make('ip_address')->label('Adresse IP')->disabled(),
            Textarea::make('description')->label('Description')->rows(2)->disabled()->columnSpanFull(),
            KeyValue::make('old_values')->label('Anciennes valeurs')->disabled()->columnSpanFull(),
            KeyValue::make('new_values')->label('Nouvelles valeurs')->disabled()->columnSpanFull(),
        ]);
    }
}