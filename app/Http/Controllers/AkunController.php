<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;

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
        // Menghapus 'balance' dan menambahkan validasi 'type_valid'
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'type'        => 'required|in:cash,bank,ewallet', 
            'type_valid'  => 'required|string|max:100', 
            'account_no'  => 'nullable|string|max:50',         
        ]);

        $validated['user_id'] = auth()->id();
        Akun::create($validated);

        return redirect()->route('akun.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function edit(Akun $akun)
    {
        if ($akun->user_id !== auth()->id()) abort(403);
        return view('Wallet.account_edit', compact('akun'));
    }

    public function update(Request $request, Akun $akun)
    {
        if ($akun->user_id !== auth()->id()) abort(403);

        // Menghapus 'balance' dan menambahkan validasi 'type_valid'
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'type'        => 'required|in:cash,bank,ewallet', 
            'type_valid'  => 'required|string|max:100',
            'account_no'  => 'nullable|string|max:50',         
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