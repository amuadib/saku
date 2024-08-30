<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengaduanResource\Pages;
use App\Models\Pengaduan;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Infolists;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

class PengaduanResource extends Resource
{
    protected static ?string $model = Pengaduan::class;
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('siswa.nama')
                    ->weight('bold'),
                TextEntry::make('created_at')
                    ->label('Tanggal')
                    ->date('d F Y H:i:s'),
                TextEntry::make('laporan')
                    ->columnSpanFull(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->modifyQueryUsing(function (Builder $query) {
                if (!auth()->user()->isAdmin()) {
                    return $query
                        ->where('lembaga_id', auth()->user()->authable->lembaga_id);
                }
            })
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('no')
                    ->rowIndex(),
                TextColumn::make('siswa.nama'),
                TextColumn::make('laporan'),
                TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //     Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengaduan::route('/'),
            'view' => Pages\ViewPengaduan::route('/{record}'),
        ];
    }
}
