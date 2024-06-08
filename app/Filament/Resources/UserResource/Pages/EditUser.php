<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Anggota;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['authable'] = Anggota::find($data['authable_id']);

        return $data;
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        Anggota::find($data['authable_id'])
            ->update($data['authable']);

        unset($data['authable']);
        if ($data['password'] == null) {
            unset($data['password']);
        }
        unset($data['password_confirmation']);
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
