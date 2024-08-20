<?php

namespace App\Filament\Resources\KasResource\Pages;

use App\Filament\Resources\KasResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKas extends CreateRecord
{
    protected static string $resource = KasResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!auth()->user()->isAdmin()) {
            $data['lembaga_id'] = auth()->user()->authable->lembaga_id;
        }
        return $data;
    }
}
