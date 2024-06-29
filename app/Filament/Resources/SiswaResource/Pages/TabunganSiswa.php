<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use App\Models\Kas;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;

class TabunganSiswa extends Page implements HasForms
{
    use InteractsWithRecord, InteractsWithForms;

    protected static string $resource = SiswaResource::class;
    protected static string $view = 'filament.resources.siswa-resource.pages.tabungan-siswa';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
    public function getHeading(): string
    {
        return 'Tabungan ' . $this->record->nama_siswa;
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Radio::make('kas_id')
                    ->label('Kas')
                    ->options(function (): array {
                        $data = [];
                        $lembaga = config('custom.lembaga');
                        foreach (Kas::where('tabungan', true)
                            ->when(!auth()->user()->isAdmin(), function ($w) {
                                $w->where('lembaga_id', auth()->user()->authable->lembaga_id);
                            })
                            ->get() as $k) {
                            $data[] = $k->nama . ' - ' . $lembaga[$k->lembaga_id];
                        }
                        return $data;
                    })
                    ->inline()
                    ->inlineLabel(false),
                Forms\Components\Radio::make('mutasi')
                    ->options(['m' => 'Setoran', 'k' => 'Pengambilan'])
                    ->inline()
                    ->inlineLabel(false),
                Forms\Components\TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('keterangan'),
            ])
            ->statePath('data');
    }
}
