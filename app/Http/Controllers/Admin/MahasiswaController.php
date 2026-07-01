<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMahasiswaRequest;
use App\Http\Requests\Admin\UpdateMahasiswaRequest;
use App\Models\Mahasiswa;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class MahasiswaController extends Controller
{
    public function index(): Response
    {
        $mahasiswa = Mahasiswa::with('user')->latest()->paginate(20);

        return Inertia::render('admin/mahasiswa/Index', [
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/mahasiswa/Create');
    }

    public function store(StoreMahasiswaRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $roleMahasiswa = Role::where('nama_role', 'mahasiswa')->firstOrFail();

            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role_id'  => $roleMahasiswa->id,
            ]);

            $mahasiswa = Mahasiswa::create([
                'user_id'       => $user->id,
                'nim'           => $request->nim,
                'nama'          => $request->nama,
                'email'         => $request->email,
                'program_studi' => $request->program_studi,
                'angkatan'      => $request->angkatan,
            ]);

            AuditLogService::log('INSERT', 'mahasiswa', $mahasiswa->id, null, $mahasiswa->toArray());
        });

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function edit(Mahasiswa $mahasiswa): Response
    {
        return Inertia::render('admin/mahasiswa/Edit', [
            'mahasiswa' => $mahasiswa->load('user'),
        ]);
    }

    public function update(UpdateMahasiswaRequest $request, Mahasiswa $mahasiswa): RedirectResponse
    {
        $lama = $mahasiswa->toArray();

        DB::transaction(function () use ($request, $mahasiswa) {
            $mahasiswa->update($request->only(['nim', 'nama', 'email', 'program_studi', 'angkatan']));

            if ($mahasiswa->user) {
                $mahasiswa->user->update([
                    'name'  => $request->nama,
                    'email' => $request->email,
                ]);
            }
        });

        AuditLogService::log('UPDATE', 'mahasiswa', $mahasiswa->id, $lama, $mahasiswa->fresh()->toArray());

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa): RedirectResponse
    {
        $lama = $mahasiswa->toArray();
        $mahasiswa->delete();

        AuditLogService::log('DELETE', 'mahasiswa', $mahasiswa->id, $lama, null);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus.');
    }
}