<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Filament\Resources\TransaksiResource;
use App\Models\Kas;
use App\Models\RekapTransaksiHarian;
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
        $user = auth()->user();
        $data['kode'] = \App\Traits\TransaksiTrait::getKodeTransaksi(
            prefix: $data['mutasi'] == 'm' ? 'MTX' : 'KTX',
            lembaga_id: $user->isAdmin() ? $data['lembaga_id'] : $user->authable->lembaga_id
        );
        $data['transable_type'] = 'App\\Models\\Kas';
        $data['transable_id'] = $data['kas_id'];
        $data['user_id'] = $user->id;

        //update Kas
        if ($data['mutasi'] == 'm') {
            Kas::find($data['kas_id'])->increment('saldo', $data['jumlah']);
        } else {
            Kas::find($data['kas_id'])->decrement('saldo', $data['jumlah']);
        }

        // Input ke Rekap Transaksi
        \App\Traits\RekapTransaksiTrait::updateRekapTransaksi(
            $data['kas_id'],
            $data['mutasi'],
            $data['jumlah']
        );

        unset($data['lembaga_id'], $data['kas_id'], $data['mutasi']);
        return $data;
    }
}
