<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBarang extends CreateRecord
{
    protected static string $resource = BarangResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $servis_barang = new \App\Services\BarangService;
    //     $data['kode'] = $data['kode'] . rand(111, 999) . $servis_barang->getIdBarang();
    //     return $data;
    // }
}
