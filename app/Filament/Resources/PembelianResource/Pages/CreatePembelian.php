<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use App\Models\Barang;
use App\Models\DetailPembelian;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreatePembelian extends CreateRecord
{
    protected static string $resource = PembelianResource::class;
    protected static ?string $title = 'Input Pembelian';
    protected static bool $canCreateAnother = false;
    protected $detail;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $total_pembelian = 0;
        $data['kode'] = \App\Services\PembelianService::getKode($data['lembaga_id']);
        $n = 0;
        foreach ($data['barang'] as $b) {
            $this->detail[$n]['barang_id'] = $b['barang_id'];
            $this->detail[$n]['jumlah'] = $b['jumlah'];
            $this->detail[$n]['harga'] = $b['harga_beli'];
            $this->detail[$n]['total'] = $b['jumlah'] * $b['harga_beli'];
            $total_pembelian += $b['jumlah'] * $b['harga_beli'];
            $barang = Barang::find($b['barang_id']);
            $barang->update([
                'harga_beli' => $b['harga_beli'],
                'harga' => $b['harga_jual'],
                'stok' => $barang->stok + $b['jumlah']
            ]);
            $n++;
        }
        $data['total'] = $total_pembelian;
        $data['user_id'] = auth()->user()->id;
        unset($data['barang'], $data['lembaga_id']);
        return $data;
    }
    protected function afterCreate(): void
    {
        $detail = [];
        $n = 0;
        foreach ($this->detail as $b) {
            $detail[$n]['id'] = Str::orderedUuid();
            $detail[$n]['pembelian_id'] = $this->record->id;
            $detail[$n]['barang_id'] = $b['barang_id'];
            $detail[$n]['jumlah'] = $b['jumlah'];
            $detail[$n]['harga'] = $b['harga'];
            $detail[$n]['total'] = $b['jumlah'] * $b['harga'];
            $n++;
        }
        DetailPembelian::insert($detail);
    }
}
