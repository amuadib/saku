<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Filament\Resources\TransaksiResource;
use App\Models\Kas;
use App\Models\Transaksi;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaksi extends CreateRecord
{
    protected static string $resource = TransaksiResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        if ($data['jenis'] == 'tf') {
            //keluarkan dana dari Kas Sumber
            Transaksi::create([
                'kode' => \App\Traits\TransaksiTrait::getKodeTransaksi(
                    prefix: 'KTX',
                    lembaga_id: $user->isAdmin() ? $data['lembaga_id'] : $user->authable->lembaga_id
                ),
                'jumlah' => $data['jumlah'],
                'keterangan' => $data['keterangan'],
                'transable_type' => 'App\\Models\\Kas',
                'transable_id' => $data['kas_id'],
                'user_id' => $user->id,
            ]);
            $this->updateDanRekap(
                'k',
                $data['kas_id'],
                $data['jumlah']
            );

            $data['mutasi'] = 'm';
            $data['kas_id'] = $data['kas_id_tujuan'];
        }

        $data['kode'] = \App\Traits\TransaksiTrait::getKodeTransaksi(
            prefix: $data['mutasi'] == 'm' ? 'MTX' : 'KTX',
            lembaga_id: $user->isAdmin() ? $data['lembaga_id'] : $user->authable->lembaga_id
        );
        $data['transable_type'] = 'App\\Models\\Kas';
        $data['transable_id'] = $data['kas_id'];
        $data['user_id'] = $user->id;

        $this->updateDanRekap(
            $data['mutasi'],
            $data['kas_id'],
            $data['jumlah']
        );

        unset($data['lembaga_id'], $data['kas_id'], $data['mutasi'], $data['jenis'], $data['kas_id_tujuan']);
        return $data;
    }

    private function updateDanRekap($mutasi, $kas_id, $jumlah)
    {
        //update Kas
        if ($mutasi == 'm') {
            Kas::find($kas_id)->increment('saldo', $jumlah);
        } else {
            Kas::find($kas_id)->decrement('saldo', $jumlah);
        }
        // Input ke Rekap Transaksi
        \App\Traits\RekapTransaksiTrait::updateRekapTransaksi(
            $kas_id,
            $mutasi,
            $jumlah
        );
    }
}
