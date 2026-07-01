<?php

namespace Database\Seeders;

use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class KrsSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswaList  = Mahasiswa::all();
        $mataKuliahList = MataKuliah::all();

        foreach ($mahasiswaList as $mahasiswa) {
            // Setiap mahasiswa ambil 3-5 mata kuliah acak
            $picked = $mataKuliahList->random(min(mt_rand(3, 5), $mataKuliahList->count()));

            foreach ($picked as $mk) {
                Krs::firstOrCreate([
                    'mahasiswa_id'   => $mahasiswa->id,
                    'mata_kuliah_id' => $mk->id,
                ]);
            }
        }
    }
}