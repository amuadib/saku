<?php

namespace App\Filament\Resources\TabunganResource\Pages;

use App\Filament\Resources\TabunganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTabungan extends EditRecord
{
    protected static string $resource = TabunganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
