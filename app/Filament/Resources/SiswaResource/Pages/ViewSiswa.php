<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSiswa extends ViewRecord
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('warning'),
            Actions\Action::make('api')
                ->label('Update data dari API Master Data')
                ->icon('heroicon-o-cloud-arrow-down')
                ->color('info')
                ->action(function ($record) {
                    try {
                        $service = app(\App\Services\MasterDataService::class);
                        $result = $service->updateSiswaFromApi($record);

                        \Filament\Notifications\Notification::make()
                            ->title($result['message'])
                            ->icon('heroicon-o-check-circle')
                            ->iconColor('success')
                            ->send();
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Gagal Update dari API')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
