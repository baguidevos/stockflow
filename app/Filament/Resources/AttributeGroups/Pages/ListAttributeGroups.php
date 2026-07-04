<?php

namespace App\Filament\Resources\AttributeGroups\Pages;

use App\Filament\Resources\AttributeGroups\AttributeGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttributeGroups extends ListRecords
{
    protected static string $resource = AttributeGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}