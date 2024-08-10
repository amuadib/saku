<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Filament\Support\RawJs;

class TransaksiChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'transaksiChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Grafik Transaksi Mingguan';
    protected static ?string $pollingInterval = '30s';
    protected static ?int $sort = 3;
    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $masuk = $keluar = $labels = [];

        $start = (date('D') != 'Sun') ? date('Y-m-d', strtotime('last Sunday')) : date('Y-m-d');
        $finish = (date('D') != 'Sat') ? date('Y-m-d', strtotime('next Saturday')) : date('Y-m-d');

        foreach (Transaksi::when(
            !auth()->user()->isAdmin(),
            function ($w) {
                $w
                    ->whereRaw('SUBSTR(`kode`,4,1) = ' . auth()->user()->authable->lembaga_id);
            }
        )
            ->whereBetween('created_at', [$start, $finish])
            ->groupByRaw('substr(created_at,1,10)')
            ->orderBy('created_at')
            ->selectRaw("
    `created_at` AS `tanggal`,
    SUM(IF(SUBSTR(`kode`,1,1)='M',`jumlah`,0)) AS `masuk`,
    SUM(IF(SUBSTR(`kode`,1,1)='K',`jumlah`,0)) AS `keluar`")
            ->get() as $t) {
            $masuk[] = $t->masuk;
            $keluar[] = $t->keluar;
            $labels[] = date('d/m/Y', strtotime($t->tanggal));
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'colors' => ["#4aca5b", "#f85c44"],
            'series' => [
                [
                    'name' => 'Pemasukan',
                    'data' => $masuk,
                ],
                [
                    'name' => 'Pengeluaran',
                    'data' => $keluar,
                ],
            ],
            'xaxis' => [
                'categories' => $labels,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
        ];
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
        dataLabels: {
            enabled: true,
            formatter: function (val, opt) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(val)
            },
            dropShadow: {
                enabled: true
            },
        }
    }
    JS);
    }
}
