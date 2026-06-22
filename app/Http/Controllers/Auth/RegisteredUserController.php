<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'country_code' => ['required', 'in:+62,+65,+1,+60'],
            'mobile_number' => ['required', 'numeric', 'digits_between:5,15'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Membersihkan angka 0 di awal nomor jika user menyertakannya secara tidak sengaja
        $cleanNumber = ltrim($request->mobile_number, '0');
        
        // Menggabungkan kode negara dan nomor telepon (Contoh: +62812345678)
        $fullMobileNumber = $request->country_code . $cleanNumber;

        // Validasi tambahan untuk memastikan nomor gabungan unik di database jika diperlukan
        $request->merge(['full_mobile_number' => $fullMobileNumber]);
        $request->validate([
            'full_mobile_number' => ['unique:'.User::class.',mobile_number'],
        ], [
            'full_mobile_number.unique' => 'Nomor handphone ini sudah terdaftar.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $fullMobileNumber,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}