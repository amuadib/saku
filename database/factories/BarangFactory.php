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
        return [
            'id' => Str::orderedUuid(),
            'nama' => fake()->words(rand(3, 7), true),
            'jenis' => array_rand(config('custom.barang.jenis')),
            'harga' => rand(1, 10) * 1000,
            'stok' => rand(10, 100),
            'satuan' => array_rand(config('custom.barang.satuan')),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
    }
}
