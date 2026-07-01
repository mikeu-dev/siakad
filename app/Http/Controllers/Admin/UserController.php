<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        $users = User::with('role')->latest()->paginate(20);
        $roles = Role::all();

        return Inertia::render('admin/users/Index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function create(): Response
    {
        $roles = Role::all();

        return Inertia::render('admin/users/Create', [
            'roles' => $roles,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role_id'  => ['required', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id'  => $validated['role_id'],
        ]);

        AuditLogService::log('INSERT', 'users', $user->id, null, ['name' => $user->name, 'email' => $user->email, 'role_id' => $user->role_id]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): Response
    {
        $roles = Role::all();

        return Inertia::render('admin/users/Edit', [
            'user'  => $user->load('role'),
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'unique:users,email,'.$user->id],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $lama = ['name' => $user->name, 'email' => $user->email, 'role_id' => $user->role_id];
        $user->update($validated);

        AuditLogService::log('UPDATE', 'users', $user->id, $lama, ['name' => $user->name, 'email' => $user->email, 'role_id' => $user->role_id]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $lama = ['name' => $user->name, 'email' => $user->email];
        $user->delete();

        AuditLogService::log('DELETE', 'users', $user->id, $lama, null);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}