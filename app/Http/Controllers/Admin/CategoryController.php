<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategori::withCount('products');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        
        $sortBy = $request->get('sort', 'nama_kategori');
        $sortOrder = $request->get('order', 'asc');
        
        if (in_array($sortBy, ['nama_kategori', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('nama_kategori');
        }
        
        $perPage = $request->get('per_page', 10);
        $categories = $perPage == 'all' ? $query->get() : $query->paginate((int)$perPage);
        
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:kategori,slug',
            'deskripsi' => 'nullable|string',
        ]);
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['nama_kategori']);
        }
        
        Kategori::create($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function show($id)
    {
        $category = Kategori::with(['products' => function($query) {
            $query->withCount('variants')->latest();
        }])->findOrFail($id);
        
        return view('admin.categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Kategori::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Kategori::findOrFail($id);
        
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:kategori,slug,' . $id . ',id_kategori',
            'deskripsi' => 'nullable|string',
        ]);
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['nama_kategori']);
        }
        
        $category->update($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy($id)
    {
        $category = Kategori::findOrFail($id);
        
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk!');
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
