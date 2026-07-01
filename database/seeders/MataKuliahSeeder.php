<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        $list = [
            ['kode' => 'MK001', 'nama' => 'Pemrograman Web',              'sks' => 3, 'semester' => 3],
            ['kode' => 'MK002', 'nama' => 'Basis Data',                   'sks' => 3, 'semester' => 3],
            ['kode' => 'MK003', 'nama' => 'Algoritma dan Pemrograman',    'sks' => 4, 'semester' => 1],
            ['kode' => 'MK004', 'nama' => 'Jaringan Komputer',            'sks' => 3, 'semester' => 4],
            ['kode' => 'MK005', 'nama' => 'Kecerdasan Buatan',            'sks' => 3, 'semester' => 5],
            ['kode' => 'MK006', 'nama' => 'Sistem Operasi',               'sks' => 3, 'semester' => 4],
            ['kode' => 'MK007', 'nama' => 'Rekayasa Perangkat Lunak',     'sks' => 3, 'semester' => 5],
            ['kode' => 'MK008', 'nama' => 'Matematika Diskrit',           'sks' => 3, 'semester' => 2],
            ['kode' => 'MK009', 'nama' => 'Statistika',                   'sks' => 3, 'semester' => 2],
            ['kode' => 'MK010', 'nama' => 'Keamanan Sistem Informasi',    'sks' => 3, 'semester' => 6],
        ];

        foreach ($list as $mk) {
            MataKuliah::firstOrCreate(['kode' => $mk['kode']], $mk);
        }
    }
}