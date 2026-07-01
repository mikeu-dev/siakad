<?php

namespace Database\Seeders;

use App\Models\Keuangan;
use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class KeuanganSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswaList = Mahasiswa::all();
        $keteranganList = [
            'SPP Semester Ganjil',
            'SPP Semester Genap',
            'Biaya Praktikum',
            'Biaya Wisuda',
            'Biaya Registrasi',
        ];
        $nominalList = [2500000, 3000000, 3500000, 5000000, 750000];
        $count = 0;

        foreach ($mahasiswaList as $mahasiswa) {
            // Setiap mahasiswa dapat 2-3 tagihan
            $jumlah = mt_rand(2, 3);
            for ($j = 0; $j < $jumlah && $count < 50; $j++) {
                Keuangan::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'keterangan'   => $keteranganList[($count) % count($keteranganList)],
                    'nominal'      => $nominalList[array_rand($nominalList)],
                    'status'       => (mt_rand(0, 1) ? 'sudah_bayar' : 'belum_bayar'),
                ]);
                $count++;
            }
        }
    }
}