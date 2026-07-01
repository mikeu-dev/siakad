<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'                       => fake()->name(),
            'email'                      => fake()->unique()->safeEmail(),
            'email_verified_at'          => now(),
            'password'                   => static::$password ??= Hash::make('password'),
            'remember_token'             => Str::random(10),
            'two_factor_secret'          => null,
            'two_factor_recovery_codes'  => null,
            'two_factor_confirmed_at'    => null,
            'role_id'                    => null,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_secret'          => encrypt('secret'),
            'two_factor_recovery_codes'  => encrypt(json_encode(['recovery-code-1'])),
            'two_factor_confirmed_at'    => now(),
        ]);
    }

    public function administrator(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('nama_role', 'administrator')->value('id'),
        ]);
    }

    public function dosen(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('nama_role', 'dosen')->value('id'),
        ]);
    }

    public function mahasiswaRole(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('nama_role', 'mahasiswa')->value('id'),
        ]);
    }
}
