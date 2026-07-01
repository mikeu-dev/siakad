<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class KrsController extends Controller
{
    private const MAX_SKS = 24;

    public function index(Request $request): Response
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        $krs = Krs::with('mataKuliah')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->get();

        $totalSks = $krs->sum(fn ($k) => $k->mataKuliah->sks ?? 0);

        $ambilIds = $krs->pluck('mata_kuliah_id')->toArray();
        $available = MataKuliah::whereNotIn('id', $ambilIds)->orderBy('nama')->get();

        return Inertia::render('mahasiswa/krs/Index', [
            'krs'      => $krs,
            'totalSks' => $totalSks,
            'maxSks'   => self::MAX_SKS,
            'available' => $available,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'mata_kuliah_id' => ['required', 'exists:mata_kuliah,id'],
        ]);

        // Cek duplikat
        $already = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('mata_kuliah_id', $validated['mata_kuliah_id'])
            ->exists();

        if ($already) {
            return back()->with('error', 'Mata kuliah sudah diambil.');
        }

        // Cek total SKS
        $mk = MataKuliah::findOrFail($validated['mata_kuliah_id']);
        $totalSks = Krs::with('mataKuliah')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->get()
            ->sum(fn ($k) => $k->mataKuliah->sks ?? 0);

        if ($totalSks + $mk->sks > self::MAX_SKS) {
            return back()->with('error', 'Maksimum '.self::MAX_SKS.' SKS terlampaui.');
        }

        $krs = Krs::create([
            'mahasiswa_id'   => $mahasiswa->id,
            'mata_kuliah_id' => $validated['mata_kuliah_id'],
        ]);

        AuditLogService::log('INSERT', 'krs', $krs->id, null, $krs->toArray());

        return back()->with('success', 'Mata kuliah berhasil diambil.');
    }

    public function destroy(Request $request, Krs $krs): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        if ($krs->mahasiswa_id !== $mahasiswa->id) {
            abort(403, 'Akses ditolak.');
        }

        $lama = $krs->toArray();
        $krs->delete();

        AuditLogService::log('DELETE', 'krs', $krs->id, $lama, null);

        return back()->with('success', 'Mata kuliah berhasil dibatalkan.');
    }
}