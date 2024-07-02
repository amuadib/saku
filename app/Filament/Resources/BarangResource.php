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

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $recordTitleAttribute = 'nama';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('foto')
                    ->columnSpanFull(),
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
                    ->columnSpan(3),
                Forms\Components\Radio::make('satuan')
                    ->options(config('custom.barang.satuan'))
                    ->inline()
                    ->inlineLabel(false)
                    ->required()
                    ->columnSpan(3),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
                TextInput::make('stok_minimal')
                    ->numeric()
                    ->default(0)
                    ->columnSpan(6),
            ])
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('jenis')
                    ->formatStateUsing(fn (string $state): string => config('custom.barang.jenis')[$state]),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('harga')
                    ->prefix('Rp ')
                    ->numeric(thousandsSeparator: '.'),
                TextColumn::make('stok')
                    ->state(fn (Barang $b): string => $b->stok . ' ' . config('custom.barang.satuan')[$b->satuan])
                    ->color(fn (Barang $b): string|null => $b->stok <= $b->stok_minimal ? 'danger' : null)
                    ->description(fn (Barang $b): string|null => $b->stok <= $b->stok_minimal ? 'Stok kurang dari stok minimal' : null),
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
                            ->default(fn (Barang $b): string => $b->nama)
                            ->columnSpan(6),
                        TextInput::make('harga')
                            ->required()
                            ->default(fn (Barang $b): string => $b->harga)
                            ->prefix('Rp ')
                            ->currencyMask('.', ',', 0)
                            ->columnSpan(6),
                        TextInput::make('stok')
                            ->required()
                            ->default(fn (Barang $b): string => $b->stok)
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan(3),
                    ])
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
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'view' => Pages\ViewBarang::route('/{record}'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
