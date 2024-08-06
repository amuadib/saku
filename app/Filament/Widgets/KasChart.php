<?php

namespace App\Filament\Widgets;

use App\Models\Kas;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Filament\Forms\Components\Radio;
use Filament\Support\RawJs;

class KasChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'kasChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Saldo Kas';
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '30s';

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
        foreach (Kas::when(
            !$isAdmin,
            function ($w) {
                $w->where('lembaga_id', auth()->user()->authable->lembaga_id);
            },
            function ($w) {
                $w->where('lembaga_id',  $this->filterFormData['lembaga_id']);
            }
        )->get() as $k) {
            $series[] = intval($k->saldo);
            $labels[] = $k->nama;
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
                '#269ffb',
                '#d730eb',
                '#26e7a5',
                '#febb3b',
                '#ff6077',
                '#8b75d7',
                '#6d838d',
                '#46b3a9',
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
