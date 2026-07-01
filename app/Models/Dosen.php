<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $nidn
 * @property string $nama
 * @property string $email
 */
class Dosen extends Model
{
    protected $table = 'dosen';

    protected $fillable = ['user_id', 'nidn', 'nama', 'email'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }
}