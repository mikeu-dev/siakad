<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    protected $model = Mahasiswa::class;

    public function definition(): array
    {
        $prodiList = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Ilmu Komputer',
            'Teknik Elektro',
            'Manajemen',
        ];

        return [
            'user_id'       => null,
            'nim'           => fake()->unique()->numerify('##########'),
            'nama'          => fake()->name(),
            'email'         => fake()->unique()->safeEmail(),
            'program_studi' => fake()->randomElement($prodiList),
            'angkatan'      => fake()->randomElement([2021, 2022, 2023, 2024]),
        ];
    }
}