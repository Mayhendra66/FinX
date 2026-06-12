<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AkunController extends Controller
{
    public function index()
    {
        $akun = Akun::where('user_id', auth()->id())->get();
        return view('Wallet.account', compact('akun'));
    }

    public function create()
    {
        return view('Wallet.account_create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'type'        => 'required|in:cash,bank,ewallet', // Menggunakan karakter minus (-) sesuai enum db
            'account_no'  => 'nullable|string|max:50',         // Penambahan kolom nomor rekening
            'balance'     => 'required|numeric|min:0',         // Validasi presisi desimal
        ]);

        $validated['user_id'] = auth()->id();
        Akun::create($validated);

        return redirect()->route('akun.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function transfer(Request $request)
    {
        $userId = auth()->id(); 

        $request->validate([
            'from_account_id' => "required|exists:akun,id,user_id,{$userId}",
            'to_account_id'   => "required|exists:akun,id,user_id,{$userId}|different:from_account_id",
            'amount'          => 'required|numeric|min:1',
        ]);

        $nominal = $request->amount;

        try {
            DB::transaction(function () use ($request, $nominal, $userId) {
                // 1. Total Mines (Akun Pengirim)
                $pengirim = Akun::where('user_id', $userId)->findOrFail($request->from_account_id);
                
                if ($pengirim->balance < $nominal) {
                    throw new \Exception('Saldo rekening pengirim tidak mencukupi.');
                }
                $pengirim->decrement('balance', $nominal);

                // 2. Total Up (Akun Penerima)
                $penerima = Akun::where('user_id', $userId)->findOrFail($request->to_account_id);
                $penerima->increment('balance', $nominal);
            });

            return redirect()->back()->with('success', 'Transfer aset berhasil dieksekusi.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit(Akun $akun)
    {
        if ($akun->user_id !== auth()->id()) abort(403);
        return view('Wallet.account_edit', compact('akun'));
    }

    public function update(Request $request, Akun $akun)
    {
        if ($akun->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'type'        => 'required|in:cash,bank,ewallet', // Menggunakan karakter minus (-) sesuai enum db
            'account_no'  => 'nullable|string|max:50',         // Penambahan kolom nomor rekening
            'balance'     => 'required|numeric|min:0',
        ]);

        $akun->update($validated);
        return redirect()->route('akun.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(Akun $akun)
    {
        if ($akun->user_id !== auth()->id()) abort(403);
        $akun->delete();
        return redirect()->route('akun.index')->with('success', 'Akun berhasil dihapus.');
    }
}