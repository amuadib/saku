<?php

namespace App\Filament\Resources\TabunganResource\Pages;

use App\Filament\Resources\TabunganResource;
use App\Jobs\ProsesPotonganTabungan;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListTabungans extends ListRecords
{
    protected static string $resource = TabunganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('potongan')
                ->label('Admin Tabungan')
                // ->visible(fn(): bool => date('Y', strtotime(config('custom.tabungan.potongan.tanggal'))) !== date('Y'))
                ->color('danger')
                ->icon('heroicon-o-calculator')
                ->action(function () {
                    $tanggal = strtotime(config('custom.tabungan.potongan.tanggal'));
                    if (date('Y', $tanggal) >= date('Y')) {
                        Notification::make()
                            ->title('Administrasi Tabungan sudah dipotong pada ' . date('d F Y', $tanggal))
                            ->icon('heroicon-o-exclamation-triangle')
                            ->iconColor('warning')
                            ->send();
                        return;
                    }

                    ProsesPotonganTabungan::dispatch(auth()->user()->id);
                }),
        ];
    }
}
