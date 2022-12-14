<?php

namespace App\Filament\Resources\ItemGroupResource\Pages;

use App\Filament\Resources\ItemGroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemGroup extends EditRecord
{
    protected static string $resource = ItemGroupResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
