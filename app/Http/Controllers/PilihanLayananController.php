<?php

namespace App\Http\Controllers;

use App\Models\MainAccountModel;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PilihanLayananController extends Controller
{
    public function pulsa()
    {
        return redirect()->route('dashboard', ['open_modal' => 'pulsa']);
    }

    public function pln()
    {
        return redirect()->route('dashboard', ['open_modal' => 'pln']);
    }

    public function tv()
    {
        return redirect()->route('dashboard', ['open_modal' => 'tv']);
    }

    public function storePulsa(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|min:10',
            'pulsa_type' => 'required|string|in:pulsa,data',
            'nominal_pulsa' => 'required_if:pulsa_type,pulsa|string',
            'nominal_paket' => 'required_if:pulsa_type,data|string',
        ]);

        $phone = $request->input('phone_number');
        $type = $request->input('pulsa_type');

        if ($type === 'pulsa') {
            $nominal = $request->input('nominal_pulsa');
            $prices = [
                '10000' => 10500,
                '25000' => 25500,
                '50000' => 50000,
                '100000' => 99000,
            ];
            $amount = $prices[$nominal] ?? null;
            if (!$amount) {
                return redirect()->back()->with('error', 'Pilihan nominal pulsa tidak valid.');
            }
            $note = "Pembelian Pulsa Rp " . number_format((int)$nominal, 0, ',', '.') . " ke " . $phone;
        } else {
            $nominal = $request->input('nominal_paket');
            $prices = [
                '5gb' => 45000,
                '12gb' => 85000,
            ];
            $amount = $prices[$nominal] ?? null;
            if (!$amount) {
                return redirect()->back()->with('error', 'Pilihan paket data tidak valid.');
            }
            $packageName = $nominal === '5gb' ? 'Paket 5GB / 30 Hari' : 'Paket 12GB / 30 Hari';
            $note = "Pembelian " . $packageName . " ke " . $phone;
        }

        $userId = Auth::id();
        $mainAccount = MainAccountModel::where('user_id', $userId)->first();

        if (!$mainAccount) {
            return redirect()->back()->with('error', 'Rekening utama tidak ditemukan.');
        }

        if ($mainAccount->balance < $amount) {
            return redirect()->back()->with('error', 'Saldo utama tidak mencukupi.');
        }

        DB::transaction(function () use ($mainAccount, $amount, $userId, $note) {
            $mainAccount->decrement('balance', $amount);

            Transaction::create([
                'user_id'          => $userId,
                'account_id'       => null,
                'category_id'      => null,
                'type'             => 'expense',
                'amount'           => $amount,
                'transaction_date' => now()->toDateString(),
                'note'             => $note,
                'receipt_image'    => null,
                'is_installment'   => 0,
                'installment_id'   => null,
                'savings_goal_id'  => null,
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil: ' . $note);
    }

    public function storePln(Request $request)
    {
        $request->validate([
            'meter_number' => 'required|string|min:8',
            'nominal_pln' => 'required|numeric',
        ]);

        $meter = $request->input('meter_number');
        $nominal = (int)$request->input('nominal_pln');

        $allowedNominal = [20000, 50000, 100000, 500000];
        if (!in_array($nominal, $allowedNominal)) {
            return redirect()->back()->with('error', 'Pilihan nominal token PLN tidak valid.');
        }

        $amount = $nominal;
        $token = implode(' - ', array_map(function () {
            return rand(1000, 9999);
        }, range(1, 5)));

        $userId = Auth::id();
        $mainAccount = MainAccountModel::where('user_id', $userId)->first();

        if (!$mainAccount) {
            return redirect()->back()->with('error', 'Rekening utama tidak ditemukan.');
        }

        if ($mainAccount->balance < $amount) {
            return redirect()->back()->with('error', 'Saldo utama tidak mencukupi.');
        }

        $note = "Pembelian Token PLN Rp " . number_format($nominal, 0, ',', '.') . " (Meter: " . $meter . ")";

        DB::transaction(function () use ($mainAccount, $amount, $userId, $note, $token) {
            $mainAccount->decrement('balance', $amount);

            Transaction::create([
                'user_id'          => $userId,
                'account_id'       => null,
                'category_id'      => null,
                'type'             => 'expense',
                'amount'           => $amount,
                'transaction_date' => now()->toDateString(),
                'note'             => $note . " - Token: " . $token,
                'receipt_image'    => null,
                'is_installment'   => 0,
                'installment_id'   => null,
                'savings_goal_id'  => null,
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Token PLN berhasil dibeli! Token Anda: ' . $token);
    }

    public function storeTv(Request $request)
    {
        $request->validate([
            'provider_tv' => 'required|string|in:indihome,biznet,firstmedia,myrepublic',
            'customer_number' => 'required|string|min:6',
        ]);

        $provider = $request->input('provider_tv');
        $customer = $request->input('customer_number');

        $prices = [
            'indihome' => 349000,
            'biznet' => 412500,
            'firstmedia' => 299000,
            'myrepublic' => 319000,
        ];

        $amount = $prices[$provider] ?? null;
        if (!$amount) {
            return redirect()->back()->with('error', 'Penyedia layanan TV & Internet tidak valid.');
        }

        $providerName = ucfirst($provider);
        if ($provider === 'firstmedia') $providerName = "First Media";
        if ($provider === 'myrepublic') $providerName = "MyRepublic";
        if ($provider === 'indihome') $providerName = "IndiHome";
        if ($provider === 'biznet') $providerName = "Biznet Home";

        $userId = Auth::id();
        $mainAccount = MainAccountModel::where('user_id', $userId)->first();

        if (!$mainAccount) {
            return redirect()->back()->with('error', 'Rekening utama tidak ditemukan.');
        }

        if ($mainAccount->balance < $amount) {
            return redirect()->back()->with('error', 'Saldo utama tidak mencukupi.');
        }

        $note = "Pembayaran Tagihan " . $providerName . " No. Pelanggan: " . $customer;

        DB::transaction(function () use ($mainAccount, $amount, $userId, $note) {
            $mainAccount->decrement('balance', $amount);

            Transaction::create([
                'user_id'          => $userId,
                'account_id'       => null,
                'category_id'      => null,
                'type'             => 'expense',
                'amount'           => $amount,
                'transaction_date' => now()->toDateString(),
                'note'             => $note,
                'receipt_image'    => null,
                'is_installment'   => 0,
                'installment_id'   => null,
                'savings_goal_id'  => null,
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Pembayaran Tagihan ' . $providerName . ' berhasil!');
    }
}
