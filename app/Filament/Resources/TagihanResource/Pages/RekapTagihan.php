<?php

namespace App\Filament\Resources\TagihanResource\Pages;

use App\Filament\Resources\TagihanResource;
use Filament\Resources\Pages\Page;
use App\Models\Kas;
use App\Models\Santri;
use App\Models\Tagihan;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Table;

class RekapTagihan extends Page
implements HasTable
{
    use InteractsWithTable;
    protected static string $resource = TagihanResource::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Tagihan';
    protected static ?string $navigationLabel = 'Tagihan Rekap';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.resources.tagihan-resource.pages.rekap-tagihan';

    // protected function getTableRecordKey(): string
    // {
    //     return 'id'; // ini siswa.id dari select
    // }
    protected function getSelectedLembagaId(): ?int
    {
        $state = $this->getTableFilterState('lembaga_id');

        return filled($state['value'] ?? null)
            ? (int) $state['value']
            : 1;
    }

    protected function getKasList(): array
    {
        $lembagaId = $this->getSelectedLembagaId();

        static $localCache = [];
        if (isset($localCache[$lembagaId])) {
            return $localCache[$lembagaId];
        }

        $kas = Kas::query()
            ->where('ada_tagihan', true)
            ->where('lembaga_id', $lembagaId)
            ->pluck('nama', 'id')
            ->toArray();

        $localCache[$lembagaId] = $kas;
        return $kas;
    }

    protected function getTableQuery(): Builder
    {
        $kasList = $this->getKasList();

        $selects = [
            DB::raw('siswa.id AS id'),
            DB::raw('siswa.nama AS nama'),
        ];

        foreach ($kasList as $kasId => $nama) {
            $alias = str()->slug($nama, '_');

            $selects[] = DB::raw("
                SUM(
                    CASE 
                        WHEN tagihan.kas_id = '{$kasId}'
                        THEN tagihan.jumlah
                        ELSE 0
                    END
                ) AS {$alias}
            ");
        }

        $selects[] = DB::raw("
            SUM(
                CASE 
                    WHEN tagihan.bayar IS NULL OR tagihan.bayar = 0
                    THEN tagihan.jumlah
                    ELSE 0
                END
            ) AS total
        ");

        return Tagihan::join('siswa', 'siswa.id', '=', 'tagihan.siswa_id')
            ->join('kas', 'kas.id', '=', 'tagihan.kas_id')
            ->select($selects)
            ->where(function ($q) {
                $q->whereNull('tagihan.bayar')
                    ->orWhere('tagihan.bayar', 0);
            })
            ->groupBy('siswa.id')
            ->orderByDesc('total');
    }

    protected function getTableColumns(): array
    {
        $columns = [
            Tables\Columns\TextColumn::make('nama')
                ->label('Nama')
                ->searchable(
                    query: fn(Builder $query, string $search) =>
                    $query->where('siswa.nama', 'like', "%{$search}%")
                ),
        ];

        foreach ($this->getKasList() as $nama) {
            $columns[] = Tables\Columns\TextColumn::make(str()->slug($nama, '_'))
                ->label($nama)
                ->money('IDR');
        }

        $columns[] = Tables\Columns\TextColumn::make('total')
            ->label('Total Tunggakan')
            ->money('IDR')
            ->weight('bold');

        return $columns;
    }
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns())
            ->actions([
                Tables\Actions\Action::make('send_wa')
                    ->label('Kirim Tagihan')
                    ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
                    ->button()
                    ->modal(false)
                    ->action(function () {
                        // \Log::info('Kirim WA untuk siswa ID: ' . $record->id);
                        dd('record');
                    }),
            ]);
    }
    // protected function getTableActions(): array
    // {
    //     return [
    //         Tables\Actions\Action::make('send_wa')
    //             ->label('Kirim Tagihan')
    //             ->button()
    //             ->modal(false)
    //             ->action(fn($record) => dd($record))

    //         // Tables\Actions\Action::make('send_wa')
    //         //     ->label('Kirim Tagihan')
    //         //     ->button()
    //         //     ->icon('heroicon-o-chat-bubble-left-right')
    //         //     ->color('success')
    //         //     // ->requiresConfirmation()
    //         //     ->action(function ($record) {
    //         //         dd($record);
    //         //         $tagihans = Tagihan::with('kas')
    //         //             ->where('siswa_id', $record->id)
    //         //             ->where(
    //         //                 fn($q) =>
    //         //                 $q->whereNull('bayar')->orWhere('bayar', 0)
    //         //             )
    //         //             ->get();
    //         //         // logic kirim WA
    //         //     }),
    //     ];
    // }
    // protected function getTableFilters(): array
    // {
    //     return [
    //         Tables\Filters\SelectFilter::make('lembaga_id')
    //             ->label('Lembaga')
    //             ->options(config('custom.lembaga'))
    //             ->query(function (Builder $query, array $data) {
    //                 if (! filled($data['value'] ?? null)) {
    //                     return;
    //                 }

    //                 $query->where('kas.lembaga_id', $data['value']);
    //             })
    //             ->default(1)
    //             ->native(false),
    //     ];
    // }
    // public function updatedTableFilters(): void
    // {
    //     $this->resetTable();
    // }
}
