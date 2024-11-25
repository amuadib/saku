<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Laporan extends Page
{
    protected static ?string $title = 'Laporan Keuangan';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.laporan';
    protected static ?int $navigationSort = 6;
}
