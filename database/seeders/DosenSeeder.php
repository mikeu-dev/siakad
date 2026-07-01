<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = User::where('email', "dosen{$i}@siakad.ac.id")->firstOrFail();

            Dosen::firstOrCreate(
                ['nidn' => str_pad((string) $i, 10, '0', STR_PAD_LEFT)],
                [
                    'user_id' => $user->id,
                    'nama'    => $user->name,
                    'email'   => $user->email,
                ]
            );
        }
    }
}