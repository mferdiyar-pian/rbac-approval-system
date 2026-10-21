<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

/**
 * UserController
 *
 * Mengelola siklus hidup akun pengguna (CRUD) dan penetapan peran (Role Assignment)
 * berdasarkan prinsip Role-Based Access Control (RBAC) serta Segregation of Duties.
 * Hanya pengguna dengan permission 'manage users' (Superadmin & Admin) yang berhak mengakses.
 */
class UserController extends Controller
{
    /**
     * Tampilkan semua akun pengguna beserta relasi role, permissions, dan jumlah transaksi.
     */
    public function index()
    {
        abort_unless(
            auth()->user()->can('manage users'),
            403,
            'Kamu tidak memiliki izin mengelola pengguna.'
        );

        $users = User::with(['roles', 'permissions'])
            ->withCount('transactions')
            ->latest()
            ->get();

        return view('users.index', compact('users'));
    }

    /**
     * Tampilkan form pendaftaran akun pengguna baru.
     */
    public function create()
    {
        abort_unless(
            auth()->user()->can('manage users'),
            403,
            'Kamu tidak memiliki izin mengelola pengguna.'
        );

        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    /**
     * Simpan akun pengguna baru ke database dan tetapkan role Spatie Permission.
     */
    public function store(Request $request)
    {
        abort_unless(
            auth()->user()->can('manage users'),
            403,
            'Kamu tidak memiliki izin mengelola pengguna.'
        );

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)],
            'role'     => ['required', 'string', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        AuditLog::record('CREATE_USER', "Menambahkan akun pengguna baru: {$user->name} ({$user->email}) dengan role {$validated['role']}");

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna ' . $user->name . ' berhasil ditambahkan dengan role ' . $validated['role'] . '.');
    }

    /**
     * Tampilkan form edit akun pengguna.
     */
    public function edit(User $user)
    {
        abort_unless(
            auth()->user()->can('manage users'),
            403,
            'Kamu tidak memiliki izin mengelola pengguna.'
        );

        $roles = Role::all();
        $userRole = $user->roles->first()?->name ?? '';

        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Perbarui data akun pengguna dan role-nya.
     */
    public function update(Request $request, User $user)
    {
        abort_unless(
            auth()->user()->can('manage users'),
            403,
            'Kamu tidak memiliki izin mengelola pengguna.'
        );

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        // Proteksi: jangan izinkan mengubah role akun Superadmin utama agar akses sistem tidak terkunci
        if ($user->email === 'superadmin@example.com' && $validated['role'] !== 'Superadmin') {
            return redirect()
                ->route('users.index')
                ->with('error', 'Role akun Superadmin utama tidak dapat diubah agar akses sistem tidak terkunci.');
        }

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);
        $user->syncRoles([$validated['role']]);

        AuditLog::record('EDIT_USER', "Memperbarui data akun pengguna: {$user->name} ({$user->email}) role: {$validated['role']}");

        return redirect()
            ->route('users.index')
            ->with('success', 'Data pengguna ' . $user->name . ' berhasil diperbarui.');
    }

    /**
     * Hapus akun pengguna.
     */
    public function destroy(User $user)
    {
        abort_unless(
            auth()->user()->can('manage users'),
            403,
            'Kamu tidak memiliki izin mengelola pengguna.'
        );

        // Jangan izinkan superadmin menghapus akunnya sendiri
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Kamu tidak dapat menghapus akunmu sendiri yang sedang aktif.');
        }

        $name = $user->name;
        $email = $user->email;
        $user->delete();

        AuditLog::record('DELETE_USER', "Menghapus akun pengguna: {$name} ({$email})");

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun pengguna ' . $name . ' berhasil dihapus.');
    }
}
