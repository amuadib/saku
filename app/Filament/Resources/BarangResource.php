<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Filament\Resources\BarangResource\RelationManagers;
use App\Models\Barang;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $recordTitleAttribute = 'nama';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        $lembaga = config('custom.lembaga');
        return $form
            ->schema([
                Forms\Components\FileUpload::make('foto')
                    ->columnSpanFull(),
                Forms\Components\Radio::make('lembaga_id')
                    ->label('Lembaga')
                    ->inline()
                    ->inlineLabel(false)
                    ->options(Arr::except($lembaga, [99]))
                    ->columnSpan(6)
                    ->visible(fn(): bool => (auth()->user()->isAdmin())),
                Forms\Components\Radio::make('jenis')
                    ->label('Jenis')
                    ->options(config('custom.barang.jenis'))
                    ->inline()
                    ->inlineLabel(false)
                    ->columnSpan(6),
                TextInput::make('nama')
                    ->required()
                    ->columnSpan(6),
                TextInput::make('harga')
                    ->required()
                    ->prefix('Rp ')
                    ->currencyMask('.', ',', 0)
                    ->columnSpan(6),
                TextInput::make('stok')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->columnSpan(4),
                Forms\Components\Radio::make('satuan')
                    ->options(config('custom.barang.satuan'))
                    ->inline()
                    ->inlineLabel(false)
                    ->required()
                    ->columnSpan(4),
                TextInput::make('stok_minimal')
                    ->numeric()
                    ->default(0)
                    ->columnSpan(4),
                // TableRepeater::make('varian')
                //     ->headers([
                //         Header::make('nama'),
                //         Header::make('harga'),
                //         Header::make('stok'),
                //     ])
                //     ->schema([
                //         TextInput::make('nama')
                //             ->required()
                //             ->columnSpan(6),
                //         TextInput::make('harga')
                //             ->required()
                //             ->prefix('Rp ')
                //             ->currencyMask('.', ',', 0)
                //             ->columnSpan(6),
                //         TextInput::make('stok')
                //             ->required()
                //             ->numeric()
                //             ->minValue(0)
                //             ->columnSpan(3)
                //     ])
                //     ->columnSpanFull(),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
            ])
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->modifyQueryUsing(function (Builder $query) {
                if (!auth()->user()->isAdmin()) {
                    return $query->where('lembaga_id', auth()->user()->authable->lembaga_id);
                }
            })
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('jenis')
                    ->formatStateUsing(fn(string $state): string => config('custom.barang.jenis')[$state]),
                TextColumn::make('nama')
                    ->sortable()
                    ->searchable()
                    ->description(
                        function (Barang $b): string {
                            if ($b->varian == null) {
                                return '';
                            }
                        }
                    ),
                TextColumn::make('harga')
                    ->sortable()
                    ->prefix('Rp ')
                    ->numeric(thousandsSeparator: '.'),
                TextColumn::make('stok')
                    ->sortable()
                    ->state(fn(Barang $b): string => $b->stok . ' ' . config('custom.barang.satuan')[$b->satuan])
                    ->color(fn(Barang $b): string|null => $b->stok <= $b->stok_minimal ? 'danger' : null)
                    ->description(fn(Barang $b): string|null => $b->stok <= $b->stok_minimal ? 'Stok kurang dari stok minimal' : null),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis')
                    ->options(config('custom.barang.jenis')),
            ])
            ->actions([
                Tables\Actions\Action::make('salin_barang')
                    ->color('info')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->form([
                        TextInput::make('nama')
                            ->required()
                            ->default(fn(Barang $b): string => $b->nama)
                            ->columnSpan(6),
                        TextInput::make('harga')
                            ->required()
                            ->default(fn(Barang $b): string => $b->harga)
                            ->prefix('Rp ')
                            ->currencyMask('.', ',', 0)
                            ->columnSpan(6),
                        TextInput::make('stok')
                            ->required()
                            ->default(fn(Barang $b): string => $b->stok)
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan(3),
                    ])
                    ->visible(fn(Barang $b): bool => auth()->user()->can('update', $b))
                    ->action(function (Barang $b, array $data) {
                        if (Barang::create([
                            'jenis' => $b->jenis,
                            'foto' => $b->foto,
                            'keterangan' => $b->keterangan,
                            'satuan' => $b->satuan,
                            'stok_minimal' => $b->stok_minimal,
                            'nama' => $data['nama'],
                            'harga' => $data['harga'],
                            'stok' => $data['stok'],
                        ])) {
                            Notification::make()
                                ->title('Barang berhasil ditambahkan')
                                ->icon('heroicon-o-check-circle')
                                ->iconColor('success')
                                ->send();
                        }
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->color('warning'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn(Barang $b): bool => auth()->user()->can('update', $b)),
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
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'view' => Pages\ViewBarang::route('/{record}'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
