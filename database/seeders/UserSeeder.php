<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('nama_role', 'administrator')->firstOrFail();
        $dosenRole = Role::where('nama_role', 'dosen')->firstOrFail();
        $mhsRole   = Role::where('nama_role', 'mahasiswa')->firstOrFail();

        // 1 Administrator
        User::firstOrCreate(
            ['email' => 'admin@siakad.ac.id'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password'),
                'role_id'  => $adminRole->id,
            ]
        );

        // 10 Dosen users (linked when DosenSeeder runs)
        for ($i = 1; $i <= 10; $i++) {
            User::firstOrCreate(
                ['email' => "dosen{$i}@siakad.ac.id"],
                [
                    'name'     => "Dosen {$i}",
                    'password' => Hash::make('password'),
                    'role_id'  => $dosenRole->id,
                ]
            );
        }

        // 20 Mahasiswa users (linked when MahasiswaSeeder runs)
        for ($i = 1; $i <= 20; $i++) {
            User::firstOrCreate(
                ['email' => "mahasiswa{$i}@siakad.ac.id"],
                [
                    'name'     => "Mahasiswa {$i}",
                    'password' => Hash::make('password'),
                    'role_id'  => $mhsRole->id,
                ]
            );
        }
    }
}