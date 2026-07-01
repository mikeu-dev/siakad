<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDosenRequest;
use App\Http\Requests\Admin\UpdateDosenRequest;
use App\Models\Dosen;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class DosenController extends Controller
{
    public function index(): Response
    {
        $dosen = Dosen::with('user')->latest()->paginate(20);

        return Inertia::render('admin/dosen/Index', [
            'dosen' => $dosen,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/dosen/Create');
    }

    public function store(StoreDosenRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $roleDosen = Role::where('nama_role', 'dosen')->firstOrFail();

            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role_id'  => $roleDosen->id,
            ]);

            $dosen = Dosen::create([
                'user_id' => $user->id,
                'nidn'    => $request->nidn,
                'nama'    => $request->nama,
                'email'   => $request->email,
            ]);

            AuditLogService::log('INSERT', 'dosen', $dosen->id, null, $dosen->toArray());
        });

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function edit(Dosen $dosen): Response
    {
        return Inertia::render('admin/dosen/Edit', [
            'dosen' => $dosen->load('user'),
        ]);
    }

    public function update(UpdateDosenRequest $request, Dosen $dosen): RedirectResponse
    {
        $lama = $dosen->toArray();

        DB::transaction(function () use ($request, $dosen) {
            $dosen->update($request->only(['nidn', 'nama', 'email']));

            if ($dosen->user) {
                $dosen->user->update([
                    'name'  => $request->nama,
                    'email' => $request->email,
                ]);
            }
        });

        AuditLogService::log('UPDATE', 'dosen', $dosen->id, $lama, $dosen->fresh()->toArray());

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen): RedirectResponse
    {
        $lama = $dosen->toArray();
        $dosen->delete();

        AuditLogService::log('DELETE', 'dosen', $dosen->id, $lama, null);

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil dihapus.');
    }
}