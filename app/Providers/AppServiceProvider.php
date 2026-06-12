<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Akun;
use App\Models\Category; // Tambahkan import Model Category
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Menyediakan data $accounts dan $categories secara global untuk semua view jika user sudah login
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $accounts = Akun::where('user_id', Auth::id())->orderBy('name')->get();
                $categories = Category::where('user_id', Auth::id())->orderBy('name')->get(); // Ambil data kategori milik user

                $view->with([
                    'accounts' => $accounts,
                    'categories' => $categories
                ]);
            }
        });
    }
}