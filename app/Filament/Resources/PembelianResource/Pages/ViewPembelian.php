<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPembelian extends ViewRecord
{
    protected static string $resource = PembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make()
            //     ->color('warning'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        dd($data);
        return $data;
    }
}
