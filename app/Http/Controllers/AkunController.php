<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AkunController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'nip'      => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'jabatan'  => ['nullable', 'string', 'max:255'],
            'golongan' => ['nullable', 'string', 'max:50'],
            'bidang'   => ['nullable', 'string', 'max:255'],
        ], [
            // Custom pesan error (opsional)
            'email.required'  => 'Email wajib diisi.',
            'email.email'     => 'Format email tidak valid.',
            'email.unique'    => 'Email sudah digunakan oleh akun lain.',
            'nip.max'         => 'NIP maksimal 50 karakter.',
            'whatsapp.max'    => 'No. WhatsApp maksimal 20 karakter.',
            'jabatan.max'     => 'Jabatan maksimal 255 karakter.',
            'golongan.max'    => 'Golongan maksimal 50 karakter.',
            'bidang.max'      => 'Bidang maksimal 255 karakter.',
        ]);

        $user->fill($validated);

        // Kalau email berubah → reset verifikasi email
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('status', 'profile-updated');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ], [
            'profile_photo.required' => 'Silakan pilih foto terlebih dahulu.',
            'profile_photo.image'    => 'File harus berupa gambar.',
            'profile_photo.mimes'    => 'Foto harus berformat JPG, JPEG, PNG, atau WEBP.',
            'profile_photo.max'      => 'Ukuran foto maksimal 2 MB.',
        ]);

        $user = $request->user();

        // Hapus foto lama
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Simpan foto baru
        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        $user->profile_photo = $path;
        $user->save();

        return back()->with('status', 'profile-photo-updated');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', 'min:8'],
        ]);

        $request->user()->update([
            'password' => bcrypt($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}