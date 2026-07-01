<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $prodiList = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Ilmu Komputer',
            'Teknik Elektro',
            'Manajemen',
        ];

        for ($i = 1; $i <= 20; $i++) {
            $user = User::where('email', "mahasiswa{$i}@siakad.ac.id")->firstOrFail();

            Mahasiswa::firstOrCreate(
                ['nim' => str_pad((string) $i, 10, '2024', STR_PAD_RIGHT)],
                [
                    'user_id'       => $user->id,
                    'nama'          => $user->name,
                    'email'         => $user->email,
                    'program_studi' => $prodiList[($i - 1) % count($prodiList)],
                    'angkatan'      => 2021 + (($i - 1) % 4),
                ]
            );
        }
    }
}