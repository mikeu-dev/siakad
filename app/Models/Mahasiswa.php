<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $nim
 * @property string $nama
 * @property string $email
 * @property string $program_studi
 * @property int $angkatan
 */
class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $fillable = ['user_id', 'nim', 'nama', 'email', 'program_studi', 'angkatan'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class);
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    public function keuangan(): HasMany
    {
        return $this->hasMany(Keuangan::class);
    }
}