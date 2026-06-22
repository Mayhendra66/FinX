<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
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
        
        // 1. Validasi input nomor handphone pendukung
        $request->validate([
            'country_code' => ['required', 'in:+62,+65,+1,+60'],
            'mobile_number' => ['required', 'numeric', 'digits_between:5,15'],
        ]);

        // 2. Format ulang input nomor (hilangkan angka 0 di depan jika ada)
        $cleanNumber = ltrim($request->mobile_number, '0');
        $fullMobileNumber = $request->country_code . $cleanNumber;

        // 3. Validasi Unique Database jika nomor HP diubah
        if ($fullMobileNumber !== $user->mobile_number) {
            $request->merge(['full_mobile_number' => $fullMobileNumber]);
            $request->validate([
                'full_mobile_number' => ['unique:'.User::class.',mobile_number,'.$user->id],
            ], [
                'full_mobile_number.unique' => 'Nomor handphone ini sudah digunakan oleh akun lain.',
            ]);
        }

        // 4. Sinkronisasi data teks dari Form Request
        $user->fill($request->validated());

        // 5. Masukkan nomor HP yang telah diformat lengkap
        $user->mobile_number = $fullMobileNumber;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Proses upload foto (nullable/opsional)
        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]);

            if ($user->photo && file_exists(public_path($user->photo))) {
                unlink(public_path($user->photo));
            }

            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile'), $filename);

            $user->photo = 'uploads/profile/' . $filename;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->photo && file_exists(public_path($user->photo))) {
            unlink(public_path($user->photo));
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['success' => true]);
    }

    public function destroyPhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->photo && file_exists(public_path($user->photo))) {
            unlink(public_path($user->photo));
        }

        $user->photo = null;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'photo-deleted');
    }
}