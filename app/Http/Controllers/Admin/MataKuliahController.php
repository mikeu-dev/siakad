<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMataKuliahRequest;
use App\Http\Requests\Admin\UpdateMataKuliahRequest;
use App\Models\MataKuliah;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MataKuliahController extends Controller
{
    public function index(): Response
    {
        $mataKuliah = MataKuliah::latest()->paginate(20);

        return Inertia::render('admin/mata-kuliah/Index', [
            'mataKuliah' => $mataKuliah,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/mata-kuliah/Create');
    }

    public function store(StoreMataKuliahRequest $request): RedirectResponse
    {
        $mk = MataKuliah::create($request->validated());

        AuditLogService::log('INSERT', 'mata_kuliah', $mk->id, null, $mk->toArray());

        return redirect()->route('admin.mata-kuliah.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit(MataKuliah $mataKuliah): Response
    {
        return Inertia::render('admin/mata-kuliah/Edit', [
            'mataKuliah' => $mataKuliah,
        ]);
    }

    public function update(UpdateMataKuliahRequest $request, MataKuliah $mataKuliah): RedirectResponse
    {
        $lama = $mataKuliah->toArray();
        $mataKuliah->update($request->validated());

        AuditLogService::log('UPDATE', 'mata_kuliah', $mataKuliah->id, $lama, $mataKuliah->fresh()->toArray());

        return redirect()->route('admin.mata-kuliah.index')->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy(MataKuliah $mataKuliah): RedirectResponse
    {
        $lama = $mataKuliah->toArray();
        $mataKuliah->delete();

        AuditLogService::log('DELETE', 'mata_kuliah', $mataKuliah->id, $lama, null);

        return redirect()->route('admin.mata-kuliah.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }
}