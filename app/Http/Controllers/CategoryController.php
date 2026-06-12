<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua kategori milik user yang sedang login
        $categories = Category::where('user_id', Auth::id())
            ->orderBy('name', 'asc')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('categories.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input berdasarkan spesifikasi database dan fillable
        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:25',
                // Validasi agar tidak ada nama kategori kembar pada user_id dan type yang sama
                Rule::unique('categories')->where(function ($query) use ($request) {
                    return $query->where('user_id', Auth::id())
                                 ->where('type', $request->type);
                })
            ],
            'type' => ['required', Rule::in(['income', 'expense'])], // Validasi ENUM
            'icon' => ['nullable', 'string', 'max:50'],
        ], [
            'name.unique' => 'Kategori dengan nama ini sudah terdaftar di grup tersebut.',
            'type.in' => 'Tipe kategori harus berupa income atau expense.'
        ]);

        // Menyisipkan user_id yang sedang aktif ke dalam payload data fillable
        $validated['user_id'] = Auth::id();
        // Fallback jika icon kosong dari frontend form
        $validated['icon'] = $request->input('icon', 'tag');

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', "Kategori \"{$request->name}\" berhasil ditambahkan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('categories.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Jika ke depan Anda membutuhkan fitur edit nama kategori
        $category = Category::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:25',
                Rule::unique('categories')->ignore($category->id)->where(function ($query) use ($category) {
                    return $query->where('user_id', Auth::id())
                                 ->where('type', $category->type);
                })
            ],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Mencari kategori berdasarkan ID dan memastikan itu milik user yang sedang login (keamanan data)
        $category = Category::where('user_id', Auth::id())->findOrFail($id);
        
        $categoryName = $category->name;
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', "Kategori \"{$categoryName}\" berhasil dihapus.");
    }
}