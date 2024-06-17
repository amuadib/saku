<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Filament\Resources\SiswaResource\RelationManagers;
use App\Models\Kelas;
use App\Models\Periode;
use App\Models\Siswa;
use Faker\Core\Color;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Support\Arr;
use Filament\Forms\Get;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;
    protected static ?string $recordTitleAttribute = 'nama_siswa';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('foto')
                    ->nullable()
                    ->image()
                    ->imageEditor()
                    ->directory('foto'),
                Radio::make('lembaga_id')
                    ->label('Lembaga')
                    ->inline()
                    ->inlineLabel(false)
                    ->options(Arr::except(config('custom.lembaga'), [99]))
                    ->required()
                    ->visible(fn (): bool => (auth()->user()->isAdmin())),

                Forms\Components\ViewField::make('lembaga_id')
                    ->view('filament.form.components.view_only', [
                        'label' => 'Lembaga',
                        'value' => config('custom.lembaga')[auth()->user()->authable->lembaga_id],
                    ])
                    ->hidden(fn (): bool => (auth()->user()->isAdmin())),

                Forms\Components\Select::make('kelas_id')
                    ->label('Kelas')
                    ->options(
                        function (Get $get): array {
                            $data = [];
                            $lembaga_id = auth()->user()->isAdmin() ? $get('lembaga_id') : auth()->user()->authable->lembaga_id;
                            foreach (Kelas::getDaftarKelas($lembaga_id)->get() as $k) {
                                $data[$k->id] = $k->nama . ' - ' . $k->nama_periode;
                            }
                            return $data;
                        }
                    )
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                Radio::make('jenis_kelamin')
                    ->options(['l' => 'Laki-laki', 'p' => 'Perempuan'])
                    ->inline()
                    ->inlineLabel(false)
                    ->required(),
                TextInput::make('nik')
                    ->label('NIK')
                    ->required(),
                TextInput::make('tempat_lahir'),
                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->native(false)
                    ->displayFormat('d/m/Y'),
                Forms\Components\Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('nama_ayah'),
                TextInput::make('nama_ibu'),
                TextInput::make('telepon'),
                TextInput::make('email')
                    ->email(),
                Radio::make('status')
                    ->options(config('custom.siswa.status'))
                    ->inline()
                    ->inlineLabel(false)
                    ->default(99),
                Forms\Components\CheckboxList::make('label')
                    ->options(config('custom.siswa.label'))
                    ->columns(2)
                    ->gridDirection('row'),
            ]);
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
            ->defaultSort('nama')
            ->columns([
                TextColumn::make('no')
                    ->rowIndex(),
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),
                TextColumn::make('kelas.nama')
                    ->label('Kelas'),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('nama_ibu')
                    ->label('Ibu'),
                TextColumn::make('telepon'),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => config('custom.siswa.status')[$state])
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '2' => 'warning',
                        '3' => 'info',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\Filter::make('label_filter')
                    ->form([
                        Forms\Components\CheckboxList::make('label')
                            ->columns(2)
                            ->gridDirection('row')
                            ->options(config('custom.siswa.label'))
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query->whereJsonContains('label', $data['label'])),
                SelectFilter::make('lembaga_id')
                    ->label('Lembaga')
                    ->options(Arr::except(config('custom.lembaga'), [99])),
                SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->multiple()
                    ->preload()
                    ->options(
                        function (): array {
                            $data = [];
                            $lembaga = Arr::except(config('custom.lembaga'), [99]);
                            $periode = Periode::where('aktif', 1)->first();
                            $lembaga_id = auth()->user()->isAdmin() ? null : auth()->user()->authable->lembaga_id;

                            if ($periode and $periode->kelas->count() > 0) {
                                foreach ($periode->kelas as $k) {
                                    if ($lembaga_id !== null and $lembaga_id != $k->lembaga_id) {
                                        continue;
                                    }
                                    $data[$k->id] = $k->nama . ' - ' . $periode->nama . ' - ' . explode(' ', $lembaga[$k->lembaga_id])[0];
                                }
                            }
                            return $data;
                        }
                    ),
                SelectFilter::make('status')
                    ->options(config('custom.siswa.status')),
            ])
            ->actions([
                Tables\Actions\Action::make('tabungan')
                    ->icon('heroicon-m-credit-card')
                    ->iconButton()
                    ->url(fn (Siswa $record): string => "siswas/{$record->id}/tabungan"),
                // Tables\Actions\Action::make('tagihan')
                //     ->icon('heroicon-m-shopping-cart')
                //     ->color('danger')
                //     ->iconButton()
                //     ->form([
                //         // Forms\Components\Select::make('kas_id')
                //         //     ->relationship('kas', 'id')
                //         //     ->required(),
                //         TextInput::make('jumlah')
                //             ->required()
                //             ->numeric(),
                //         Forms\Components\Textarea::make('keterangan'),
                //     ]),
                Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->color('warning'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\ImageEntry::make('foto')
                    ->width(170)
                    ->height(230)
                    ->defaultImageUrl(url('/storage/no_photo.jpg'))
                    ->columnSpanFull(),
                TextEntry::make('kelas.nama')
                    ->label('Kelas')
                    ->state(fn (Siswa $record): string => $record->kelas->nama . ' ' . config('custom.lembaga')[$record->lembaga_id]),
                TextEntry::make('nama'),
                TextEntry::make('jenis_kelamin')
                    ->formatStateUsing(fn (string $state): string => ['l' => 'Laki-laki', 'p' => 'Perempuan'][$state]),
                TextEntry::make('nik')
                    ->label('NIK'),
                TextEntry::make('tempat_lahir'),
                TextEntry::make('tanggal_lahir')
                    ->date('d F Y'),
                TextEntry::make('alamat')
                    ->columnSpanFull(),
                TextEntry::make('nama_ayah'),
                TextEntry::make('nama_ibu'),
                TextEntry::make('telepon'),
                TextEntry::make('email')
                    ->placeholder('Email belum diisi'),
                TextEntry::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => config('custom.siswa.status')[$state])
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '2' => 'warning',
                        '3' => 'info',
                        default => 'gray',
                    }),
                TextEntry::make('label')
                    ->placeholder('Belum ada Label')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => config('custom.siswa.label')[$state])
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'warning',
                        '2' => 'warning',
                        '3' => 'danger',
                        '11' => 'info',
                        default => 'gray'
                    }),
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'view' => Pages\ViewSiswa::route('/{record}'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
            'tabungan' => Pages\TabunganSiswa::route('/{record}/tabungan'),
        ];
    }
}
