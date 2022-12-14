<?php

namespace App\Filament\Resources\ItemGroupResource\Pages;

use App\Filament\Resources\ItemGroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemGroups extends ListRecords
{
    protected static string $resource = ItemGroupResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
