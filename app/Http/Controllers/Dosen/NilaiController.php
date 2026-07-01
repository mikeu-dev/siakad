<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class NilaiController extends Controller
{
    public function index(Request $request): Response
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->firstOrFail();

        $nilai = Nilai::with(['mahasiswa', 'mataKuliah'])
            ->where('dosen_id', $dosen->id)
            ->latest()
            ->paginate(30);

        $mahasiswaList = Mahasiswa::orderBy('nama')->get(['id', 'nim', 'nama']);
        $mataKuliahList = MataKuliah::orderBy('nama')->get(['id', 'kode', 'nama']);

        return Inertia::render('dosen/nilai/Index', [
            'nilai'          => $nilai,
            'mahasiswaList'  => $mahasiswaList,
            'mataKuliahList' => $mataKuliahList,
            'dosenId'        => $dosen->id,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'mahasiswa_id'   => ['required', 'exists:mahasiswa,id'],
            'mata_kuliah_id' => ['required', 'exists:mata_kuliah,id'],
            'nilai'          => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        // Use stored procedure for nilai input/update with finalisasi validation
        DB::statement('CALL sp_input_nilai(?, ?, ?, ?)', [
            $validated['mahasiswa_id'],
            $validated['mata_kuliah_id'],
            $dosen->id,
            $validated['nilai'],
        ]);

        $record = Nilai::where('mahasiswa_id', $validated['mahasiswa_id'])
            ->where('mata_kuliah_id', $validated['mata_kuliah_id'])
            ->first();

        AuditLogService::log('INSERT_OR_UPDATE', 'nilai', $record?->id, null, $validated);

        return back()->with('success', 'Nilai berhasil disimpan.');
    }

    public function finalisasi(Request $request, Nilai $nilai): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->firstOrFail();

        if ($nilai->dosen_id !== $dosen->id) {
            abort(403, 'Anda tidak berhak memfinalisasi nilai ini.');
        }

        $lama = $nilai->toArray();
        $nilai->update(['finalisasi' => true]);

        AuditLogService::log('FINALISASI', 'nilai', $nilai->id, $lama, $nilai->fresh()->toArray());

        return back()->with('success', 'Nilai berhasil difinalisasi.');
    }
}