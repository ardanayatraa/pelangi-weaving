<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use App\Models\Jenis;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomOrder::with(['pelanggan', 'jenis', 'admin']);
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by jenis
        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('id_jenis', $request->jenis);
        }
        
        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_custom_order', 'like', "%{$search}%")
                  ->orWhere('nama_custom', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', function($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        if (in_array($sortBy, ['created_at', 'status', 'harga_final'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }
        
        $perPage = $request->get('per_page', 15);
        $customOrders = $perPage == 'all' ? $query->get() : $query->paginate((int)$perPage);
        
        $jenisOptions = Jenis::where('status', 'active')->get();
        $statusOptions = [
            'draft' => 'Draft',
            'pending_approval' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'in_production' => 'Dalam Produksi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'rejected' => 'Ditolak'
        ];
        
        return view('admin.custom-orders.index', compact('customOrders', 'jenisOptions', 'statusOptions'));
    }

    public function show($nomorCustomOrder)
    {
        $customOrder = CustomOrder::with(['pelanggan', 'jenis', 'admin'])
            ->where('nomor_custom_order', $nomorCustomOrder)
            ->firstOrFail();
            
        return view('admin.custom-orders.show', compact('customOrder'));
    }

    public function updateStatus(Request $request, $nomorCustomOrder)
    {
        $customOrder = CustomOrder::where('nomor_custom_order', $nomorCustomOrder)->firstOrFail();
        
        $validated = $request->validate([
            'status' => 'required|in:draft,pending_approval,approved,in_production,completed,cancelled,rejected',
            'catatan_admin' => 'nullable|string|max:1000',
            'harga_final' => 'nullable|numeric|min:0'
        ]);
        
        $admin = Auth::guard('admin')->user();
        
        // Update harga final jika diisi
        if (isset($validated['harga_final']) && $validated['harga_final'] > 0) {
            $customOrder->harga_final = $validated['harga_final'];
            $customOrder->dp_amount = $customOrder->calculateDpAmount();
        }
        
        // Update status dan admin yang mengupdate
        $customOrder->status = $validated['status'];
        $customOrder->updated_by = $admin->id_admin;
        
        // Update progress history
        $progressHistory = $customOrder->progress_history ?? [];
        $progressHistory[] = [
            'status' => $validated['status'],
            'catatan' => $validated['catatan_admin'],
            'admin' => $admin->nama,
            'timestamp' => now()->toISOString()
        ];
        $customOrder->progress_history = $progressHistory;
        
        $customOrder->save();
        
        return redirect()->route('admin.custom-orders.show', $nomorCustomOrder)
            ->with('success', 'Status custom order berhasil diupdate!');
    }

    public function approve(Request $request, $nomorCustomOrder)
    {
        $customOrder = CustomOrder::where('nomor_custom_order', $nomorCustomOrder)->firstOrFail();
        
        if ($customOrder->status !== 'pending_approval') {
            return back()->with('error', 'Custom order tidak dalam status menunggu persetujuan!');
        }
        
        $validated = $request->validate([
            'harga_final' => 'required|numeric|min:1000',
            'catatan_admin' => 'nullable|string|max:1000'
        ]);
        
        $admin = Auth::guard('admin')->user();
        
        $customOrder->update([
            'status' => 'approved',
            'harga_final' => $validated['harga_final'],
            'dp_amount' => ($validated['harga_final'] * 50) / 100, // 50% DP
            'updated_by' => $admin->id_admin
        ]);
        
        // Update progress history
        $progressHistory = $customOrder->progress_history ?? [];
        $progressHistory[] = [
            'status' => 'approved',
            'catatan' => $validated['catatan_admin'],
            'harga_final' => $validated['harga_final'],
            'admin' => $admin->nama,
            'timestamp' => now()->toISOString()
        ];
        $customOrder->progress_history = $progressHistory;
        $customOrder->save();
        
        return redirect()->route('admin.custom-orders.show', $nomorCustomOrder)
            ->with('success', 'Custom order berhasil disetujui!');
    }

    public function reject(Request $request, $nomorCustomOrder)
    {
        $customOrder = CustomOrder::where('nomor_custom_order', $nomorCustomOrder)->firstOrFail();
        
        if ($customOrder->status !== 'pending_approval') {
            return back()->with('error', 'Custom order tidak dalam status menunggu persetujuan!');
        }
        
        $validated = $request->validate([
            'catatan_admin' => 'required|string|max:1000'
        ]);
        
        $admin = Auth::guard('admin')->user();
        
        $customOrder->update([
            'status' => 'rejected',
            'updated_by' => $admin->id_admin
        ]);
        
        // Update progress history
        $progressHistory = $customOrder->progress_history ?? [];
        $progressHistory[] = [
            'status' => 'rejected',
            'catatan' => $validated['catatan_admin'],
            'admin' => $admin->nama,
            'timestamp' => now()->toISOString()
        ];
        $customOrder->progress_history = $progressHistory;
        $customOrder->save();
        
        return redirect()->route('admin.custom-orders.show', $nomorCustomOrder)
            ->with('success', 'Custom order berhasil ditolak!');
    }

    public function updateProgress(Request $request, $nomorCustomOrder)
    {
        $customOrder = CustomOrder::where('nomor_custom_order', $nomorCustomOrder)->firstOrFail();
        
        $validated = $request->validate([
            'progress_note' => 'required|string|max:1000',
            'progress_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $admin = Auth::guard('admin')->user();
        
        // Upload progress images
        $imagePaths = [];
        if ($request->hasFile('progress_images')) {
            foreach ($request->file('progress_images') as $image) {
                $path = $image->store('custom-orders/progress', 'public');
                $imagePaths[] = $path;
            }
        }
        
        // Update progress history
        $progressHistory = $customOrder->progress_history ?? [];
        $progressHistory[] = [
            'type' => 'progress_update',
            'note' => $validated['progress_note'],
            'images' => $imagePaths,
            'admin' => $admin->nama,
            'timestamp' => now()->toISOString()
        ];
        $customOrder->progress_history = $progressHistory;
        $customOrder->updated_by = $admin->id_admin;
        $customOrder->save();
        
        return redirect()->route('admin.custom-orders.show', $nomorCustomOrder)
            ->with('success', 'Progress berhasil diupdate!');
    }

    public function destroy($nomorCustomOrder)
    {
        $customOrder = CustomOrder::where('nomor_custom_order', $nomorCustomOrder)->firstOrFail();
        
        // Hanya bisa dihapus jika status draft atau cancelled
        if (!in_array($customOrder->status, ['draft', 'cancelled', 'rejected'])) {
            return back()->with('error', 'Custom order tidak dapat dihapus!');
        }
        
        // Hapus gambar referensi
        if ($customOrder->gambar_referensi) {
            foreach ($customOrder->gambar_referensi as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }
        
        // Hapus progress images
        if ($customOrder->progress_history) {
            foreach ($customOrder->progress_history as $history) {
                if (isset($history['images'])) {
                    foreach ($history['images'] as $imagePath) {
                        if (Storage::disk('public')->exists($imagePath)) {
                            Storage::disk('public')->delete($imagePath);
                        }
                    }
                }
            }
        }
        
        $customOrder->delete();
        
        return redirect()->route('admin.custom-orders.index')
            ->with('success', 'Custom order berhasil dihapus!');
    }
}