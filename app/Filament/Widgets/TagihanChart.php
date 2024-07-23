<?php

namespace App\Filament\Widgets;

use App\Models\Tagihan;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Filament\Forms\Components\Radio;
use Filament\Support\RawJs;

class TagihanChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'tagihanChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Tagihan';
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = null;

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $isAdmin = auth()->user()->isAdmin();
        $series = $labels = [];
        foreach (Tagihan::join('kas', 'kas.id', '=', 'kas_id')
            ->when(
                !$isAdmin,
                function ($w) {
                    $w->where('lembaga_id', auth()->user()->authable->lembaga_id);
                },
                function ($w) {
                    $w->where('lembaga_id',  $this->filterFormData['lembaga_id']);
                }
            )
            ->whereNull('bayar')
            ->groupBy('kas_id')
            ->selectRaw('kas.nama, sum(jumlah) as total')
            ->get() as $t) {
            $series[] = intval($t->total);
            $labels[] = $t->nama;
        }
        return [
            'chart' => [
                'type' => 'pie',
                'height' => 300,
            ],
            'series' => $series,
            'labels' => $labels,
            'legend' => [
                'labels' => [
                    'fontFamily' => 'inherit',
                ],
            ],
            'colors' => [
                '#8b75d7',
                '#46b3a9',
                '#d730eb',
                '#6d838d',
                '#269ffb',
                '#26e7a5',
                '#febb3b',
                '#ff6077',
                '#ea0d95',
                '#37dfbc',
            ]
        ];
    }

    protected function getFormSchema(): array
    {
        if (auth()->user()->isAdmin()) {
            return [
                Radio::make('lembaga_id')
                    ->options(config('custom.lembaga'))
                    ->default(1),

            ];
        }
        return [];
    }

    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
    {
        yaxis: {
            labels: {
                formatter: function (val, index) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(val)
                }
            }
        },
    }
    JS);
    }
}
