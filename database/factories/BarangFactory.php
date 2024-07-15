<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $barang = [
            'SRG' => [
                ['Baju', 'Celana', 'Rok'],
                ['MP', 'Identitas', 'Pramuka', 'Olahraga'],
                ['S', 'M', 'L', 'XL', 'XXL'],
            ],
            'AKS' => [
                ['Sabuk', 'Kolong hasduk']
            ],
            'LKS' => [
                [
                    'LKS B. Indonesia',
                    'LKS B. Jawa',
                    'LKS B. Inggris',
                    'LKS PKn',
                    'LKS MTK',
                ],
                [
                    'Kls 1',
                    'Kls 2',
                    'Kls 3',
                    'Kls 4',
                    'Kls 5',
                    'Kls 6',
                ]
            ],
            'USM' => [
                [
                    'Usmani Jilid 1',
                    'Usmani Jilid 2',
                    'Usmani Jilid 3',
                    'Usmani Jilid 4',
                    'Usmani Jilid 5',
                ]
            ],
            'BKU' => [
                ['Buku Gambar', 'Buku Tulis biasa', 'Buku Tulis Halus', 'Buku Kotak']
            ],
            'LLN' => [
                [
                    'Kerudung MP',
                    'Kerudung Identitas',
                    'Kerudung Pramuka',
                ]
            ]
        ];

        $jenis = array_rand(config('custom.barang.jenis'));
        $nama = '';
        foreach ($barang[$jenis] as $jns) {
            $nama .= $jns[array_rand($jns)] . ' ';
        }

        return [
            'id' => Str::orderedUuid(),
            'jenis' => $jenis,
            'nama' => trim($nama),
            'harga' => rand(2, 25) * 1000,
            'stok' => rand(10, 100),
            'satuan' => array_rand(config('custom.barang.satuan')),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
    }
}
