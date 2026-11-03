<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\ActivityLog;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('name')->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', 'in:admin,staff'],
        ]);

        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'user_created',
            'description' => 'Menambahkan pengguna: ' . $newUser->name,
            'subject_type' => User::class,
            'subject_id' => $newUser->id,
            'properties' => [
                'email' => $newUser->email,
                'role' => $newUser->role,
            ],
        ]);
        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => ['required', 'in:admin,staff'],
        ]);

        // Jangan menghapus hak Admin terakhir.
        if ($user->role === 'admin' && $validated['role'] !== 'admin') {
            $otherAdmins = User::where('role', 'admin')
                ->where('id', '!=', $user->id)
                ->where('is_active', true)
                ->count();

            if ($otherAdmins === 0) {
                return back()->withErrors([
                    'role' => 'Tidak bisa mengubah role Admin terakhir yang aktif.',
                ])->withInput();
            }
        }

        $before = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];

        $user->update($validated);

        $after = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'user_updated',
            'description' => 'Memperbarui data pengguna: ' . $user->name,
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'properties' => [
                'before' => $before,
                'after' => $after,
            ],
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'user_password_reset',
            'description' => 'Mereset password pengguna: ' . $user->name,
            'subject_type' => User::class,
            'subject_id' => $user->id,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Password pengguna berhasil direset.');
    }

    public function toggleActive(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->withErrors([
                'account' => 'Kamu tidak bisa menonaktifkan akun sendiri.',
            ]);
        }

        if ($user->role === 'admin' && $user->is_active) {
            $activeAdmins = User::where('role', 'admin')
                ->where('is_active', true)
                ->count();

            if ($activeAdmins <= 1) {
                return back()->withErrors([
                    'account' => 'Admin aktif terakhir tidak bisa dinonaktifkan.',
                ]);
            }
        }

        $oldStatus = (bool) $user->is_active;
        $newStatus = ! $oldStatus;

        $user->update(['is_active' => $newStatus]);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => $newStatus ? 'user_activated' : 'user_deactivated',
            'description' => ($newStatus ? 'Mengaktifkan' : 'Menonaktifkan')
                . ' akun pengguna: ' . $user->name,
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'properties' => [
                'before' => $oldStatus ? 'aktif' : 'nonaktif',
                'after' => $newStatus ? 'aktif' : 'nonaktif',
            ],
        ]);

        return redirect()->route('users.index')
            ->with('success', $user->is_active
                ? 'Akun berhasil diaktifkan.'
                : 'Akun berhasil dinonaktifkan.');
    }
}
