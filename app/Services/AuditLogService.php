<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public static function log(
        string $aktivitas,
        ?string $tabel = null,
        ?int $recordId = null,
        mixed $dataLama = null,
        mixed $dataBaru = null,
        ?Request $request = null,
    ): void {
        $req = $request ?? request();

        AuditLog::create([
            'user_id'    => Auth::id(),
            'aktivitas'  => $aktivitas,
            'tabel'      => $tabel,
            'record_id'  => $recordId,
            'data_lama'  => $dataLama,
            'data_baru'  => $dataBaru,
            'ip_address' => $req->ip(),
            'user_agent' => $req->userAgent(),
            'created_at' => now(),
        ]);
    }
}