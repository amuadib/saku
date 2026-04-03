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

        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = Tagihan::query()
            ->join('siswa', 'siswa.id', '=', 'tagihan.siswa_id')
            ->join('kas', 'kas.id', '=', 'tagihan.kas_id')
            ->select($selects)
            ->where(function ($q) {
                $q->whereNull('tagihan.bayar')
                    ->orWhere('tagihan.bayar', 0);
            })
            ->groupBy('siswa.id')
            ->orderByDesc('total');

        return $query;
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
                    ->action(function ($record) {
                        $siswa = \App\Models\Siswa::find($record->id);
                        $rincian = '';
                        $total = 0;
                        $no = 1;
                        foreach ($siswa->tagihan as $t) {
                            if (!$t->isLunas()) {
                                $tgl = $t->updated_at == null ? $t->created_at->format('d-m-Y') : $t->updated_at->format('d-m-Y');
                                $rincian .= $no . '. ' . ucfirst($t->keterangan) . ' (' . $tgl . '): Rp ' . number_format($t->jumlah, thousands_separator: '.') . PHP_EOL;
                                $total += $t->jumlah;
                                $no++;
                            }
                        }
                        if ($total == 0) {
                            \Filament\Notifications\Notification::make()
                                ->title('Tidak ada tagihan')
                                ->body('Siswa ' . $siswa->nama . ' tidak memiliki tagihan')
                                ->warning()
                                ->send();
                            return;
                        }
                        $nomor = env('APP_ENV') == 'local' ? env('WHATSAPP_TEST_NUMBER') : '' . $siswa->telepon;
                        $pesan = \App\Services\WhatsappService::prosesPesan(
                            siswa: $siswa,
                            data: [
                                'lembaga' => config('custom.lembaga.' . $siswa->lembaga_id),
                                'kontak.nama' => config('custom.kontak_lembaga.' . $siswa->lembaga_id . '.kontak'),
                                'tagihan.rincian' => $rincian,
                                'tagihan.total' => 'Rp ' . number_format($total, thousands_separator: '.'),
                            ],
                            jenis: $siswa->status == 3 ? 'tagihan.daftar_alumni' : 'tagihan.daftar'
                        );
                        \App\Services\WhatsappService::kirimWa(
                            nomor: $nomor,
                            pesan: $pesan,
                            sessionId: \App\Services\WhatsappService::getSessionId($siswa),
                            nama: $siswa->nama
                        );
                        \Filament\Notifications\Notification::make()
                            ->title('Tagihan dikirim')
                            ->body('Tagihan siswa ' . $siswa->nama . ' berhasil dikirim ke nomor ' . $nomor)
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public function resolveTableRecord(?string $key): ?\Illuminate\Database\Eloquent\Model
    {
        if ($key === null) {
            return null;
        }

        return $this->getTableQuery()->where('siswa.id', $key)->first();
    }
}
