<?php

namespace Database\Factories;

use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Krs>
 */
class KrsFactory extends Factory
{
    protected $model = Krs::class;

    public function definition(): array
    {
        return [
            'mahasiswa_id'   => Mahasiswa::inRandomOrder()->value('id'),
            'mata_kuliah_id' => MataKuliah::inRandomOrder()->value('id'),
        ];
    }
}