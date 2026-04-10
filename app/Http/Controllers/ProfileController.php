<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
   public function update(ProfileUpdateRequest $request): RedirectResponse
{
    // ✅ validasi tambahan
 $request->validate([
    'name' => ['required'],
    'store_description' => ['required'],
    'store_address' => ['required'],
    'phone' => ['required'],
    'latitude' => ['required'],
    'longitude' => ['required'],
    'radius' => ['required'],
    'opening_time' => ['required'],
    'closing_time' => ['required'],

    'avatar' => ['nullable', 'image', 'max:2048'],
    'store_proof' => ['nullable', 'image', 'max:2048'],
], [
    // 🔥 PESAN CUSTOM
    'name.required' => 'Nama wajib diisi',
    'store_description.required' => 'Deskripsi toko wajib diisi',
    'store_address.required' => 'Alamat wajib diisi',
    'phone.required' => 'Nomor HP wajib diisi',
    'latitude.required' => 'Lokasi belum dipilih',
    'longitude.required' => 'Lokasi belum dipilih',
    'radius.required' => 'Radius harus diisi',
    'opening_time.required' => 'Jam buka wajib diisi',
    'closing_time.required' => 'Jam tutup wajib diisi',

    'avatar.image' => 'Foto harus berupa gambar',
    'store_proof.image' => 'Bukti harus berupa gambar',
]);

    // ✅ ambil semua data dulu
    $user = $request->user();

    // ✅ isi data default (name, email, dll)
    $user->fill($request->except(['avatar', 'store_proof']));

    // ✅ tambahan manual (yang ga ada di validated)
    $user->latitude = $request->latitude;
    $user->longitude = $request->longitude;
    $user->name = $request->name;
    $user->store_description = $request->store_description;
    $user->store_address = $request->store_address;
    $user->opening_time = $request->opening_time;
    $user->closing_time = $request->closing_time;

    // ✅ upload avatar
    if ($request->hasFile('avatar')) {
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar_url = $path;
    }
    // ✅ upload store proof
    if ($request->hasFile('store_proof')) {
    $path = $request->file('store_proof')->store('proofs', 'public');
    $user->store_proof = $path;
}

    // ✅ cek email berubah
    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    // ✅ simpan semua
    $user->save();

    return Redirect::route('profile.edit')->with('status', 'profile-updated');
}

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    
}
