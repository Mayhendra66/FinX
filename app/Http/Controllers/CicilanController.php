<?php

namespace App\Http\Controllers;

use App\Models\Cicilan;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CicilanController extends Controller
{
    public function index()
    {
        $installments = Cicilan::where('user_id', auth()->id())->with('account')->orderBy('start_date', 'desc')->get();
        $accounts = Akun::where('user_id', auth()->id())->get();
        return view('installment.index', compact('installments', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'total_amount'   => 'required|numeric|min:0',
            'monthly_amount' => 'required|numeric|min:0',
            'total_months'   => 'required|integer|min:1',
            'paid_months'    => 'required|integer|min:0',
            'start_date'     => 'required|date',
            'status'         => 'nullable|string',
            'account_id'     => 'required|integer', 
        ]);

        $validated['status'] = $request->has('status') ? 'lunas' : 'belum lunas';
        $validated['user_id'] = auth()->id(); 

        if ($validated['paid_months'] > 0) {
            $deduction = $validated['monthly_amount'] * $validated['paid_months'];
            $account = Akun::findOrFail($validated['account_id']);

            // PERBAIKAN: Mengubah $account->saldo menjadi $account->balance
            if ($account->balance < $deduction) {
                return redirect()->back()->withInput()->with('error_saldo', 'Saldo akun anda tidak cukup');
            }
        }

        DB::transaction(function () use ($validated) {
            Cicilan::create($validated);
            if ($validated['paid_months'] > 0) {
                $deduction = $validated['monthly_amount'] * $validated['paid_months'];
                // PERBAIKAN: Mengubah 'saldo' menjadi 'balance'
                Akun::where('id', $validated['account_id'])->decrement('balance', $deduction);
            }
        });

        return redirect()->route('cicilan.index')->with('success', 'Data cicilan berhasil disimpan.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'paid_months' => 'required|integer|min:0',
        ]);

        $installment = Cicilan::findOrFail($id);
        $oldPaidMonths = $installment->paid_months;
        $newPaidMonths = $request->input('paid_months');

        if ($newPaidMonths >= $installment->total_months) {
            $newPaidMonths = $installment->total_months;
            $status = 'lunas';
        } else {
            $status = 'belum lunas';
        }

        $diffMonths = $newPaidMonths - $oldPaidMonths;

        if ($diffMonths > 0 && $installment->account_id) {
            $deduction = $installment->monthly_amount * $diffMonths;
            $account = Akun::findOrFail($installment->account_id);

            // PERBAIKAN: Mengubah $account->saldo menjadi $account->balance
            if ($account->balance < $deduction) {
                return redirect()->back()->with('error_saldo', 'Saldo akun anda tidak cukup');
            }
        }

        DB::transaction(function () use ($installment, $oldPaidMonths, $newPaidMonths, $status, $diffMonths) {
            $installment->update([
                'paid_months' => $newPaidMonths,
                'status'      => $status,
            ]);

            if ($diffMonths > 0 && $installment->account_id) {
                $deduction = $installment->monthly_amount * $diffMonths;
                // PERBAIKAN: Mengubah 'saldo' menjadi 'balance'
                Akun::where('id', $installment->account_id)->decrement('balance', $deduction);
            }
        });

        return redirect()->route('cicilan.index')->with('success', 'Angsuran berhasil diperbarui.');
    }

   public function destroy(string $id)
    {
        $installment = Cicilan::findOrFail($id);
        $installment->delete();

        // Mengembalikan flash session 'success' yang akan otomatis memicu SweetAlert Sukses di view
        return redirect()->route('cicilan.index')->with('success', 'Data cicilan berhasil dihapus.');
    }
}