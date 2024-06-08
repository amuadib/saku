<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Anggota;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $anggota = Anggota::create($data['authable']);

        $data['authable_type'] = 'App\\Models\\Anggota';
        $data['authable_id'] = $anggota->id;
        unset($data['authable']);
        unset($data['password_confirmation']);

        return $data;
    }
}
