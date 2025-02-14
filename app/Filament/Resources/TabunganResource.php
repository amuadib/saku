<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TabunganResource\Pages;
use App\Filament\Resources\TabunganResource\RelationManagers;
use App\Models\Tabungan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;

class TabunganResource extends Resource
{
    protected static ?string $model = Tabungan::class;
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('siswa.nama')
                    ->label('Nama'),
                TextEntry::make('siswa.kelas.nama')
                    ->label('Kelas'),
                TextEntry::make('saldo')
                    ->prefix('Rp ')
                    ->numeric(0),

                \Filament\Infolists\Components\ViewEntry::make('mutasi')
                    ->state(fn(Tabungan $t) => $t->id)
                    ->view('filament.infolists.entries.mutasi-tabungan')
                    ->columnSpanFull(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.nama')
                    ->sortable()
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('siswa.kelas.nama')
                    ->sortable()
                    ->label('Kelas'),
                TextColumn::make('kas.nama'),
                TextColumn::make('saldo')
                    ->sortable()
                    ->prefix('Rp ')
                    ->numeric(0),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ])
        ;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTabungans::route('/'),
            'create' => Pages\CreateTabungan::route('/create'),
            'view' => Pages\ViewTabungan::route('/{record}'),
            'edit' => Pages\EditTabungan::route('/{record}/edit'),
        ];
    }
}
