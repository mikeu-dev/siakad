<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Krs;
use App\Models\Nilai;
use Illuminate\Database\Seeder;

class NilaiSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil KRS yang sudah ada sebagai dasar input nilai
        $krsRecords = Krs::with('mataKuliah')->inRandomOrder()->take(50)->get();
        $dosenIds   = Dosen::pluck('id')->toArray();
        $count      = 0;

        foreach ($krsRecords as $krs) {
            if ($count >= 50) {
                break;
            }

            // Hindari duplikat (mahasiswa + mata_kuliah harus unique)
            $exists = Nilai::where('mahasiswa_id', $krs->mahasiswa_id)
                ->where('mata_kuliah_id', $krs->mata_kuliah_id)
                ->exists();

            if ($exists) {
                continue;
            }

            Nilai::create([
                'mahasiswa_id'   => $krs->mahasiswa_id,
                'mata_kuliah_id' => $krs->mata_kuliah_id,
                'dosen_id'       => $dosenIds[array_rand($dosenIds)],
                'nilai'          => round(mt_rand(5500, 10000) / 100, 2),
                'finalisasi'     => (bool) mt_rand(0, 1),
            ]);

            $count++;
        }
    }
}