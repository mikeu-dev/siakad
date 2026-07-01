<?php

namespace Database\Factories;

use App\Models\Keuangan;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Keuangan>
 */
class KeuanganFactory extends Factory
{
    protected $model = Keuangan::class;

    public function definition(): array
    {
        $keteranganList = [
            'SPP Semester Ganjil',
            'SPP Semester Genap',
            'Biaya Praktikum',
            'Biaya Wisuda',
            'Biaya Registrasi',
        ];

        return [
            'mahasiswa_id' => Mahasiswa::inRandomOrder()->value('id'),
            'keterangan'   => fake()->randomElement($keteranganList),
            'nominal'      => fake()->randomElement([2500000, 3000000, 3500000, 5000000, 750000]),
            'status'       => fake()->randomElement(['belum_bayar', 'sudah_bayar']),
        ];
    }
}