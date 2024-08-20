<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KasResource\Pages;
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
use Filament\Notifications\Notification;
use Illuminate\Support\Arr;

class KasResource extends Resource
{
    protected static ?string $model = Kas::class;
    protected static ?string $recordTitleAttribute = 'nama';
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->required(),
                Forms\Components\Radio::make('lembaga_id')
                    ->label('Lembaga')
                    ->inline()
                    ->inlineLabel(false)
                    ->options(Arr::except(config('custom.lembaga'), [99]))
                    ->required()
                    ->live()
                    ->visible(fn(): bool => (auth()->user()->isAdmin())),

                Forms\Components\ViewField::make('lembaga_id')
                    ->view('filament.forms.components.view_only', [
                        'label' => 'Lembaga',
                        'value' => config('custom.lembaga')[auth()->user()->authable->lembaga_id],
                    ])
                    ->hidden(fn(): bool => (auth()->user()->isAdmin())),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull()
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
                    ->formatStateUsing(fn(string $state): string => config('custom.lembaga')[$state]),
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
                Tables\Actions\Action::make('setor')
                    ->label('Setor Dana')
                    ->icon('heroicon-o-receipt-refund')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function (Kas $kas) {
                        $saldo = $kas->saldo;
                        \App\Traits\TransaksiTrait::prosesTransaksi(
                            kas_id: $kas->id,
                            mutasi: 'K',
                            jenis: 'TX',
                            transable_id: $kas->id,
                            jumlah: $saldo,
                            keterangan: 'Setoran dana ' . $kas->nama . ' ke Yayasan'
                        );
                        $kas->update([
                            'saldo' => 0
                        ]);
                        Notification::make()
                            ->title('Sukses')
                            ->body('Dana Kas ' . $kas->nama . ' sebesar Rp ' . number_format($saldo, thousands_separator: '.') . ' berhasil disetorkan')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(): bool => auth()->user()->isAdmin()),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->color('warning'),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
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
