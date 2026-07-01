<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $mahasiswa_id
 * @property string $keterangan
 * @property float $nominal
 * @property string $status
 */
class Keuangan extends Model
{
    protected $table = 'keuangan';

    protected $fillable = ['mahasiswa_id', 'keterangan', 'nominal', 'status'];

    protected function casts(): array
    {
        return [
            'nominal' => 'float',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}