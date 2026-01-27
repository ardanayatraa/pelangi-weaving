<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jenis;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JenisController extends Controller
{
    public function index(Request $request)
    {
        $query = Jenis::query();
        
        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_jenis', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        if (in_array($sortBy, ['nama_jenis', 'status', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }
        
        $perPage = $request->get('per_page', 15);
        $jenis = $perPage == 'all' ? $query->get() : $query->paginate((int)$perPage);
        
        return view('admin.jenis.index', compact('jenis'));
    }

    public function create()
    {
        return view('admin.jenis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:100|unique:jenis,nama_jenis',
            'deskripsi' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive'
        ]);
        
        $validated['slug'] = Str::slug($validated['nama_jenis']);
        
        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Jenis::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        Jenis::create($validated);
        
        return redirect()->route('admin.jenis.index')
            ->with('success', 'Jenis berhasil ditambahkan!');
    }

    public function show(Jenis $jenis)
    {
        $jenis->load(['customOrders', 'products']);
        return view('admin.jenis.show', compact('jenis'));
    }

    public function edit(Jenis $jenis)
    {
        return view('admin.jenis.edit', compact('jenis'));
    }

    public function update(Request $request, Jenis $jenis)
    {
        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:100|unique:jenis,nama_jenis,' . $jenis->id_jenis . ',id_jenis',
            'deskripsi' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive'
        ]);
        
        // Update slug if nama_jenis changed
        if ($validated['nama_jenis'] !== $jenis->nama_jenis) {
            $validated['slug'] = Str::slug($validated['nama_jenis']);
            
            // Ensure slug is unique
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Jenis::where('slug', $validated['slug'])->where('id_jenis', '!=', $jenis->id_jenis)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }
        
        $jenis->update($validated);
        
        return redirect()->route('admin.jenis.index')
            ->with('success', 'Jenis berhasil diupdate!');
    }

    public function destroy(Jenis $jenis)
    {
        // Check if jenis has related custom orders or products
        if ($jenis->customOrders()->exists() || $jenis->products()->exists()) {
            return back()->with('error', 'Jenis tidak dapat dihapus karena masih memiliki custom order atau produk terkait!');
        }
        
        $jenis->delete();
        
        return redirect()->route('admin.jenis.index')
            ->with('success', 'Jenis berhasil dihapus!');
    }
}