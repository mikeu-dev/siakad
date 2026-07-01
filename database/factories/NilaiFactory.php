<?php

namespace Database\Factories;

use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Nilai>
 */
class NilaiFactory extends Factory
{
    protected $model = Nilai::class;

    public function definition(): array
    {
        return [
            'mahasiswa_id'   => Mahasiswa::inRandomOrder()->value('id'),
            'mata_kuliah_id' => MataKuliah::inRandomOrder()->value('id'),
            'dosen_id'       => Dosen::inRandomOrder()->value('id'),
            'nilai'          => fake()->randomFloat(2, 55, 100),
            'finalisasi'     => fake()->boolean(30),
        ];
    }
}