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
                ->icon('heroicon-o-plus')
                ->color('info'),
            Actions\ImportAction::make()
                ->importer(SiswaImporter::class)
                ->icon('heroicon-o-document-plus')
                ->color('success')
                ->visible(fn (): bool => (auth()->user()->isAdmin()))
        ];
    }
}
