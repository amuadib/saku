<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Imports\SiswaImporter;
use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Daftarkan Siswa')
                ->icon('heroicon-o-plus')
                ->color('info')
                ->disabled(env('TAMBAH_SISWA_ENABLED', false)),
            Actions\ImportAction::make()
                ->importer(SiswaImporter::class)
                ->icon('heroicon-o-document-plus')
                ->color('success')
                ->visible(fn(): bool => (auth()->user()->isAdmin()))
                ->disabled(env('IMPOR_SISWA_ENABLED', false)),
            Actions\Action::make('sync_from_master')
                ->label('Sinkron dari Master Data')
                ->icon('heroicon-o-cloud-arrow-down')
                ->color('warning')
                ->visible(fn(): bool => (auth()->user()->isAdmin()))
                ->action(function () {
                    try {
                        $service = app(\App\Services\MasterDataService::class);
                        $result = $service->syncSiswaFromApi();

                        $body = $result['message'];
                        if ($result['error_count'] > 0) {
                            \Illuminate\Support\Facades\Log::info($result['errors']);
                            $body .= "\n\nDetail Error:";
                            foreach ($result['errors'] as $error) {
                                $body .= "\n- " . $error['nama'] . ": " . $error['error'];
                            }
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Sinkronisasi Master Data')
                            ->body($body)
                            ->icon('heroicon-o-check-circle')
                            ->iconColor($result['error_count'] > 0 ? 'warning' : 'success')
                            ->send();
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Gagal Sinkron dari Master Data')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
