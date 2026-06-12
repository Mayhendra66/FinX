<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use App\Models\Category;
use Illuminate\Http\Request;

class AnggaranController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->get('filter_month', now()->month);
        $selectedYear  = $request->get('filter_year', now()->year);
        $showAll       = $request->boolean('show_all');

        $editableBudget = null;
        if ($request->has('edit')) {
            // Scope ke user_id biar ga bisa akses punya orang lain
            $editableBudget = Anggaran::with('category')
                ->where('user_id', auth()->id())
                ->find($request->edit);
        }

        if ($editableBudget) {
            $budgets = Anggaran::with('category')
                ->where('user_id', auth()->id())
                ->where('id', $editableBudget->id)
                ->get();
        } else {
            $query = Anggaran::with('category')
                ->where('user_id', auth()->id()); // 🔒 filter user

            if (!$showAll) {
                $query->where('month', $selectedMonth)
                      ->where('year', $selectedYear);
            }

            $budgets = $query->orderBy('year', 'desc')
                             ->orderBy('month', 'desc')
                             ->get();
        }

        $totalBudget = $budgets->sum('amount');
        $totalSpent  = $budgets->sum('spent');
        $categories = Category::where('type', 'expense')
    ->where('user_id', auth()->id())
    ->get();

        return view('budgeting.index', compact(
            'budgets', 'totalBudget', 'totalSpent',
            'selectedMonth', 'selectedYear',
            'editableBudget', 'categories', 'showAll'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => [
    'required',
    \Illuminate\Validation\Rule::exists('categories', 'id')
        ->where('type', 'expense')
        ->where('user_id', auth()->id()),
],
            'amount'      => 'required|numeric|min:1000',
            'month'       => 'required|integer|between:1,12',
            'year'        => 'required|integer',
        ]);

        $isExist = Anggaran::where('user_id', auth()->id()) // 🔒 scope user
            ->where('category_id', $validated['category_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->exists();

        if ($isExist) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kategori anggaran tersebut untuk periode terpilih sudah tercatat.');
        }

        Anggaran::create(array_merge($validated, [
            'user_id' => auth()->id(),
            'spent'   => 0,
        ]));

        return redirect()->route('budgeting.index', [
            'filter_month' => $validated['month'],
            'filter_year'  => $validated['year'],
        ])->with('success', 'Alokasi maksimal anggaran berhasil dibuat!');
    }

    public function update(Request $request, string $id)
    {
        // 🔒 Cuma bisa update punya sendiri, kalau bukan → 404
        $anggaran = Anggaran::where('user_id', auth()->id())
            ->findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id,type,expense',
            'amount'      => 'required|numeric|min:1000',
            'month'       => 'required|integer|between:1,12',
            'year'        => 'required|integer',
        ]);

        $isExist = Anggaran::where('user_id', auth()->id()) // 🔒 scope user
            ->where('category_id', $validated['category_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->where('id', '!=', $id)
            ->exists();

        if ($isExist) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kategori anggaran tersebut untuk periode terpilih sudah tercatat.');
        }

        $anggaran->update($validated);

        return redirect()->route('budgeting.index', [
            'filter_month' => $validated['month'],
            'filter_year'  => $validated['year'],
        ])->with('success', 'Anggaran berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        // 🔒 Cuma bisa hapus punya sendiri, kalau bukan → 404
        $anggaran = Anggaran::where('user_id', auth()->id())
            ->findOrFail($id);

        $savedMonth = $anggaran->month;
        $savedYear  = $anggaran->year;

        $anggaran->delete();

        return redirect()->route('budgeting.index', [
            'filter_month' => $savedMonth,
            'filter_year'  => $savedYear,
        ])->with('success', 'Plafon batas anggaran tersebut telah dibatalkan.');
    }

    public function create() { return redirect()->route('budgeting.index'); }
    public function show(string $id) { return redirect()->route('budgeting.index'); }
    public function edit(string $id) { return redirect()->route('budgeting.index', ['edit' => $id]); }
}