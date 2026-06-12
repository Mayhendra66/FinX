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
        $user = $request->user();
        
        // Mengisi data teks yang divalidasi
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Proses upload foto (nullable/opsional)
        if ($request->hasFile('photo')) {
            // Validasi file foto secara langsung
            $request->validate([
                'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]);

            // Hapus foto lama di direktori jika ada
            if ($user->photo && file_exists(public_path($user->photo))) {
                unlink(public_path($user->photo));
            }

            // Simpan foto baru ke public/uploads/profile
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile'), $filename);

            // Simpan path ke kolom database
            $user->photo = 'uploads/profile/' . $filename;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request)
{
    // Menggunakan validate standar agar mengembalikan error JSON saat request via AJAX
    $request->validate([
        'password' => ['required', 'current_password'],
    ]);

    $user = $request->user();

    // Hapus foto dari direktori saat akun dihapus
    if ($user->photo && file_exists(public_path($user->photo))) {
        unlink(public_path($user->photo));
    }

    Auth::logout();
    $user->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Kembalikan status sukses berupa JSON
    return response()->json(['success' => true]);
}

public function destroyPhoto(Request $request): RedirectResponse
{
    $user = $request->user();

    // Hapus file foto dari direktori jika ada
    if ($user->photo && file_exists(public_path($user->photo))) {
        unlink(public_path($user->photo));
    }

    // Set kolom foto menjadi null di database
    $user->photo = null;
    $user->save();

    return Redirect::route('profile.edit')->with('status', 'photo-deleted');
}
}