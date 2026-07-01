<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $mahasiswa_id
 * @property int $mata_kuliah_id
 * @property int $dosen_id
 * @property float $nilai
 * @property bool $finalisasi
 */
class Nilai extends Model
{
    protected $table = 'nilai';

    protected $fillable = ['mahasiswa_id', 'mata_kuliah_id', 'dosen_id', 'nilai', 'finalisasi'];

    protected function casts(): array
    {
        return [
            'nilai' => 'float',
            'finalisasi' => 'boolean',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}