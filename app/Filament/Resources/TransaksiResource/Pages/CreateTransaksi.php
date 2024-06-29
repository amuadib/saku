<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Filament\Resources\TransaksiResource;
use App\Models\Kas;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaksi extends CreateRecord
{
    protected static string $resource = TransaksiResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id'] = \App\Traits\TransaksiTrait::getIdTransaksi(
            $data['mutasi'] == 'm' ? 'MTX' : 'KTX'
        );
        $data['transable_type'] = 'App\\Models\\Kas';
        $data['transable_id'] = $data['kas_id'];
        $data['user_id'] = \Auth::user()->id;

        //update Kas
        if ($data['mutasi'] == 'm') {
            Kas::find($data['kas_id'])->increment('saldo', $data['jumlah']);
        } else {
            Kas::find($data['kas_id'])->decrement('saldo', $data['jumlah']);
        }


        unset($data['lembaga_id'], $data['kas_id']);
        return $data;
    }
}
