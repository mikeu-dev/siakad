<?php

namespace Database\Factories;

use App\Models\MataKuliah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MataKuliah>
 */
class MataKuliahFactory extends Factory
{
    protected $model = MataKuliah::class;

    private static int $counter = 1;

    public function definition(): array
    {
        $mataKuliahList = [
            ['kode' => 'MK001', 'nama' => 'Pemrograman Web', 'sks' => 3, 'semester' => 3],
            ['kode' => 'MK002', 'nama' => 'Basis Data', 'sks' => 3, 'semester' => 3],
            ['kode' => 'MK003', 'nama' => 'Algoritma dan Pemrograman', 'sks' => 4, 'semester' => 1],
            ['kode' => 'MK004', 'nama' => 'Jaringan Komputer', 'sks' => 3, 'semester' => 4],
            ['kode' => 'MK005', 'nama' => 'Kecerdasan Buatan', 'sks' => 3, 'semester' => 5],
            ['kode' => 'MK006', 'nama' => 'Sistem Operasi', 'sks' => 3, 'semester' => 4],
            ['kode' => 'MK007', 'nama' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'semester' => 5],
            ['kode' => 'MK008', 'nama' => 'Matematika Diskrit', 'sks' => 3, 'semester' => 2],
            ['kode' => 'MK009', 'nama' => 'Statistika', 'sks' => 3, 'semester' => 2],
            ['kode' => 'MK010', 'nama' => 'Keamanan Sistem Informasi', 'sks' => 3, 'semester' => 6],
        ];

        $idx = (self::$counter - 1) % count($mataKuliahList);
        $mk = $mataKuliahList[$idx];
        self::$counter++;

        return [
            'kode'     => $mk['kode'],
            'nama'     => $mk['nama'],
            'sks'      => $mk['sks'],
            'semester' => $mk['semester'],
        ];
    }
}