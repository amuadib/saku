<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $anggota = Anggota::create([
            'nama' => 'Admin',
            'lembaga_id' => 99,
            'jenis_kelamin' => 'l',
            'alamat' => fake()->address(),
            'telepon' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
        ]);
        User::create([
            'username' => 'default.admin',
            'role_id' => 1,
            'password' => 'default.admin',
            'authable_type' => 'App\\Models\\Anggota',
            'authable_id' => $anggota->id,
        ]);
    }
}
