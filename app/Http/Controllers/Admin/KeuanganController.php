<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreKeuanganRequest;
use App\Http\Requests\Admin\UpdateKeuanganRequest;
use App\Models\Keuangan;
use App\Models\Mahasiswa;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class KeuanganController extends Controller
{
    public function index(): Response
    {
        $tagihan = Keuangan::with('mahasiswa')->latest()->paginate(20);

        return Inertia::render('admin/keuangan/Index', [
            'tagihan' => $tagihan,
        ]);
    }

    public function create(): Response
    {
        $mahasiswa = Mahasiswa::orderBy('nama')->get(['id', 'nim', 'nama']);

        return Inertia::render('admin/keuangan/Create', [
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function store(StoreKeuanganRequest $request): RedirectResponse
    {
        $tagihan = Keuangan::create($request->validated());

        AuditLogService::log('INSERT', 'keuangan', $tagihan->id, null, $tagihan->toArray());

        return redirect()->route('admin.keuangan.index')->with('success', 'Tagihan berhasil dibuat.');
    }

    public function edit(Keuangan $keuangan): Response
    {
        $mahasiswa = Mahasiswa::orderBy('nama')->get(['id', 'nim', 'nama']);

        return Inertia::render('admin/keuangan/Edit', [
            'tagihan'   => $keuangan->load('mahasiswa'),
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function update(UpdateKeuanganRequest $request, Keuangan $keuangan): RedirectResponse
    {
        $lama = $keuangan->toArray();
        $keuangan->update($request->validated());

        AuditLogService::log('UPDATE', 'keuangan', $keuangan->id, $lama, $keuangan->fresh()->toArray());

        return redirect()->route('admin.keuangan.index')->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function destroy(Keuangan $keuangan): RedirectResponse
    {
        $lama = $keuangan->toArray();
        $keuangan->delete();

        AuditLogService::log('DELETE', 'keuangan', $keuangan->id, $lama, null);

        return redirect()->route('admin.keuangan.index')->with('success', 'Tagihan berhasil dihapus.');
    }
}