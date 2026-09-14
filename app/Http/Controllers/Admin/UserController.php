<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Tampilkan daftar akun (dengan search & pagination)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhere('nip', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.kelolaakun.index', compact('users', 'search'));
    }

    /**
     * Form tambah akun baru
     */
    public function create()
    {
        return view('admin.kelolaakun.create', [
            'user' => null, // null = mode TAMBAH
        ]);
    }

    /**
     * Simpan akun baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|email|max:255|unique:users,email',
            'nip'      => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:20',
            'role'     => 'required|string',
            'bidang'   => 'required|string',
            'status'   => 'required|in:aktif,nonaktif',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'nip'      => $validated['nip'] ?? null,
            'whatsapp' => $validated['whatsapp'] ?? null,
            'role'     => $validated['role'],
            'bidang'   => $validated['bidang'],
            'status'   => $validated['status'],
            'password' => $validated['password'], // di-hash otomatis lewat casts 'hashed'
            'is_admin' => $validated['role'] === 'admin',
        ]);

        return redirect()
            ->route('admin.kelolaakun.index')
            ->with('success', 'Akun baru berhasil ditambahkan!');
    }

    /**
     * Form edit akun (pakai view yang sama dengan create)
     */
    public function edit(int $id)
    {
        $user = User::findOrFail($id);

        return view('admin.kelolaakun.create', [
            'user' => $user, // ada data = mode EDIT
        ]);
    }

    /**
     * Update data akun di database
     */
    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'nip'      => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:20',
            'role'     => 'required|string',
            'bidang'   => 'required|string',
            'status'   => 'required|in:aktif,nonaktif',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name     = $validated['name'];
        $user->username = $validated['username'];
        $user->email    = $validated['email'];
        $user->nip      = $validated['nip'] ?? null;
        $user->whatsapp = $validated['whatsapp'] ?? null;
        $user->role     = $validated['role'];
        $user->bidang   = $validated['bidang'];
        $user->status   = $validated['status'];
        $user->is_admin = $validated['role'] === 'admin';

        // Update password hanya kalau diisi
        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()
            ->route('admin.kelolaakun.index')
            ->with('success', 'Data akun berhasil diperbarui!');
    }

    /**
     * Hapus akun
     */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        // Cegah admin menghapus akunnya sendiri
        if ($user->id === Auth::id()) {
            return redirect()
                ->route('admin.kelolaakun.index')
                ->with('error', 'Kamu tidak bisa menghapus akunmu sendiri!');
        }

        $user->delete();

        return redirect()
            ->route('admin.kelolaakun.index')
            ->with('success', 'Akun berhasil dihapus!');
    }
}