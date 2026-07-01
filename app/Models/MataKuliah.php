<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property int $sks
 * @property int $semester
 */
class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';

    protected $fillable = ['kode', 'nama', 'sks', 'semester'];

    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class);
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }
}