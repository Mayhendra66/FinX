<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client; // Tambahkan ini di atas
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        // Mematikan verifikasi SSL Guzzle khusus untuk redirect
        $driver = Socialite::driver('google');
        $driver->setHttpClient(new Client(['verify' => false]));
        
        return $driver->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Mematikan verifikasi SSL Guzzle khusus untuk callback
            $driver = Socialite::driver('google')->stateless();
            $driver->setHttpClient(new Client(['verify' => false]));
            
            $googleUser = $driver->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => encrypt('password_acak_123')
                ]);
            } else {
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
            }

            Auth::login($user);
            return redirect()->intended('/dashboard');

        } catch (Exception $e) {
            dd($e->getMessage()); 
            return redirect('/login')->withErrors(['email' => 'Gagal masuk menggunakan akun Google.']);
        }
    }
}