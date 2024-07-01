<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KasResource\Pages;
use App\Filament\Resources\KasResource\RelationManagers;
use App\Models\Kas;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KasResource extends Resource
{
    protected static ?string $model = Kas::class;
    protected static ?string $recordTitleAttribute = 'nama';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->required(),
                Forms\Components\Select::make('lembaga_id')
                    ->label('Lembaga')
                    ->options(config('custom.lembaga'))
                    ->required(),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
                // Forms\Components\Toggle::make('ada_tagihan')
                //     ->label('Ada tagihan ?'),
                // Forms\Components\Repeater::make('jenis_transaksi')
                //     ->schema([
                //         TextInput::make('nama')
                //     ])
                //     ->columnSpanFull()
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
            ->defaultSort('nama')
            ->columns([
                TextColumn::make('no')
                    ->rowIndex(),
                TextColumn::make('nama'),
                TextColumn::make('saldo')
                    ->numeric(0)
                    ->prefix('Rp '),
                TextColumn::make('lembaga_id')
                    ->label('Lembaga')
                    ->formatStateUsing(fn (string $state): string => config('custom.lembaga')[$state]),
                ToggleColumn::make('ada_tagihan')
                    ->label('Ada tagihan ?'),
                ToggleColumn::make('tabungan'),
                ToggleColumn::make('penjualan')
                    ->beforeStateUpdated(function ($record, $state) {
                        Kas::where('lembaga_id', $record->lembaga_id)
                            ->where('id', '<>', $record->id)
                            ->update(['penjualan' => false]);
                    }),
                TextColumn::make('keterangan'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('lembaga_id')
                    ->label('Lembaga')
                    ->options(config('custom.lembaga')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListKas::route('/'),
            'create' => Pages\CreateKas::route('/create'),
            'view' => Pages\ViewKas::route('/{record}'),
            'edit' => Pages\EditKas::route('/{record}/edit'),
        ];
    }
}
