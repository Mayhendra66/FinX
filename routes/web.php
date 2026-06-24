<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AkunController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionMobileController;
use App\Http\Controllers\CicilanController;
use App\Http\Controllers\SavingGoalController;
use App\Http\Controllers\AnalysisController;

use App\Http\Controllers\HelpdeskController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});


Route::get('/auth/google/redirect', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/categories', function () {
    return view('categories.index');
})->middleware(['auth', 'verified'])->name('categories.index');

Route::get('/budgeting', function () {
    return view('budgeting.index');
})->middleware(['auth', 'verified'])->name('budgeting.index');

Route::middleware(['auth'])->group(function () {
    Route::resource('transactions', TransactionController::class);
});


Route::middleware(['auth'])->group(function () {
    Route::resource('transactionsmobile', TransactionMobileController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::resource('cicilan', CicilanController::class);
});



Route::middleware(['auth'])->group(function () {
    Route::resource('analysis', AnalysisController::class);
});

Route::get('/payment/qris_scan', function () {
    return view('payment.qris_scan');
})->name('payment.qris_scan');

Route::get('/transfer', function () {
    return view('transfer.index');
})->name('transfer.index');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/topup', [TransactionController::class, 'topup'])->name('topup.store');
    Route::post('/qris', [TransactionController::class, 'qris'])->name('qris.store');
    Route::post('/qris/scan', [TransactionController::class, 'scan'])->name('scan.store');

    Route::get('/transfer', [TransactionController::class, 'transfer'])->name('transfer.index');
Route::get('/transfer/create/{account_id}', [TransactionController::class, 'transferCreate'])->name('transfer.create');
Route::post('/transfer/store', [TransactionController::class, 'transferStore'])->name('transfer.store');

Route::resource('saving-goals', SavingGoalController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('saving-goals/intro-seen', [SavingGoalController::class, 'markIntroSeen'])->name('saving-goals.intro-seen');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/photo', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');

    Route::resource('categories', CategoryController::class);
    Route::resource('budgeting', AnggaranController::class)->middleware(['auth', 'verified']);

    Route::resource('akun', AkunController::class);
    Route::post('/akun/transfer', [AkunController::class, 'transfer'])->name('akun.transfer');

    Route::get('/helpdesk', [HelpdeskController::class, 'index'])->name('helpdesk.index');
    Route::post('/helpdesk', [HelpdeskController::class, 'store'])->name('helpdesk.store');
});

require __DIR__.'/auth.php';
