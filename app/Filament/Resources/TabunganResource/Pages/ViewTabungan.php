<?php

namespace App\Filament\Resources\TabunganResource\Pages;

use App\Filament\Resources\TabunganResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTabungan extends ViewRecord
{
    protected static string $resource = TabunganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }
}
