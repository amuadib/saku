<?php

namespace App\Filament\Pages;

use App\Models\Kas;
use App\Models\RekapTransaksiHarian;
use Filament\Pages\Page;

class Laporan extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.laporan';
    protected static ?int $navigationSort = 5;
}
