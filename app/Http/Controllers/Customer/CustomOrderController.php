<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use App\Models\Jenis;
use App\Models\Pembayaran;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CustomOrderController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }
    public function index(Request $request)
    {
        $query = CustomOrder::with(['jenis'])
            ->where('id_pelanggan', Auth::guard('pelanggan')->id());
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $customOrders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $statusOptions = [
            'draft' => 'Draft',
            'pending_approval' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'in_production' => 'Dalam Produksi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'rejected' => 'Ditolak'
        ];
        
        return view('customer.custom-orders.index', compact('customOrders', 'statusOptions'));
    }

    public function create()
    {
        $jenisOptions = Jenis::where('status', 'active')->orderBy('nama_jenis')->get();
        return view('customer.custom-orders.create', compact('jenisOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'nama_custom' => 'required|string|max:200',
            'deskripsi_custom' => 'required|string|max:2000',
            'jumlah' => 'required|integer|min:1|max:100',
            'catatan_pelanggan' => 'nullable|string|max:1000',
            'gambar_referensi.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $validated['id_pelanggan'] = Auth::guard('pelanggan')->id();
        $validated['status'] = 'pending_approval';
        
        // Upload reference images
        $imagePaths = [];
        if ($request->hasFile('gambar_referensi')) {
            foreach ($request->file('gambar_referensi') as $image) {
                $path = $image->store('custom-orders/references', 'public');
                $imagePaths[] = $path;
            }
        }
        $validated['gambar_referensi'] = $imagePaths;
        
        $customOrder = CustomOrder::create($validated);
        
        return redirect()->route('custom-orders.show', $customOrder->nomor_custom_order)
            ->with('success', 'Custom order berhasil dibuat! Menunggu persetujuan admin.');
    }

    public function show($nomorCustomOrder)
    {
        $customOrder = CustomOrder::with(['jenis'])
            ->where('nomor_custom_order', $nomorCustomOrder)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
            
        return view('customer.custom-orders.show', compact('customOrder'));
    }

    public function edit($nomorCustomOrder)
    {
        $customOrder = CustomOrder::where('nomor_custom_order', $nomorCustomOrder)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
            
        // Hanya bisa edit jika status draft
        if ($customOrder->status !== 'draft') {
            return redirect()->route('custom-orders.show', $nomorCustomOrder)
                ->with('error', 'Custom order tidak dapat diedit!');
        }
        
        $jenisOptions = Jenis::where('status', 'active')->orderBy('nama_jenis')->get();
        return view('customer.custom-orders.edit', compact('customOrder', 'jenisOptions'));
    }

    public function update(Request $request, $nomorCustomOrder)
    {
        $customOrder = CustomOrder::where('nomor_custom_order', $nomorCustomOrder)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
            
        // Hanya bisa update jika status draft
        if ($customOrder->status !== 'draft') {
            return back()->with('error', 'Custom order tidak dapat diedit!');
        }
        
        $validated = $request->validate([
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'nama_custom' => 'required|string|max:200',
            'deskripsi_custom' => 'required|string|max:2000',
            'jumlah' => 'required|integer|min:1|max:100',
            'catatan_pelanggan' => 'nullable|string|max:1000',
            'gambar_referensi.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        // Upload new reference images
        $imagePaths = $customOrder->gambar_referensi ?? [];
        if ($request->hasFile('gambar_referensi')) {
            foreach ($request->file('gambar_referensi') as $image) {
                $path = $image->store('custom-orders/references', 'public');
                $imagePaths[] = $path;
            }
        }
        $validated['gambar_referensi'] = $imagePaths;
        
        $customOrder->update($validated);
        
        return redirect()->route('custom-orders.show', $nomorCustomOrder)
            ->with('success', 'Custom order berhasil diupdate!');
    }

    public function cancel($nomorCustomOrder)
    {
        $customOrder = CustomOrder::where('nomor_custom_order', $nomorCustomOrder)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
            
        // Hanya bisa cancel jika belum dalam produksi
        if (!in_array($customOrder->status, ['draft', 'pending_approval', 'approved'])) {
            return back()->with('error', 'Custom order tidak dapat dibatalkan!');
        }
        
        $customOrder->update(['status' => 'cancelled']);
        
        return redirect()->route('custom-orders.show', $nomorCustomOrder)
            ->with('success', 'Custom order berhasil dibatalkan!');
    }

    public function removeImage(Request $request, $nomorCustomOrder)
    {
        $customOrder = CustomOrder::where('nomor_custom_order', $nomorCustomOrder)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
            
        if ($customOrder->status !== 'draft') {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus gambar']);
        }
        
        $imageIndex = $request->input('image_index');
        $images = $customOrder->gambar_referensi ?? [];
        
        if (isset($images[$imageIndex])) {
            // Hapus file dari storage
            if (Storage::disk('public')->exists($images[$imageIndex])) {
                Storage::disk('public')->delete($images[$imageIndex]);
            }
            
            // Hapus dari array
            unset($images[$imageIndex]);
            $images = array_values($images); // Re-index array
            
            $customOrder->update(['gambar_referensi' => $images]);
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Gambar tidak ditemukan']);
    }

    public function submitForApproval($nomorCustomOrder)
    {
        $customOrder = CustomOrder::where('nomor_custom_order', $nomorCustomOrder)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
            
        if ($customOrder->status !== 'draft') {
            return back()->with('error', 'Custom order tidak dapat disubmit!');
        }
        
        $customOrder->update(['status' => 'pending_approval']);
        
        return redirect()->route('custom-orders.show', $nomorCustomOrder)
            ->with('success', 'Custom order berhasil disubmit untuk persetujuan!');
    }

    public function payDp($nomorCustomOrder)
    {
        $customOrder = CustomOrder::where('nomor_custom_order', $nomorCustomOrder)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
            
        if ($customOrder->status !== 'approved' || $customOrder->isDpPaid()) {
            return back()->with('error', 'Pembayaran DP tidak dapat dilakukan!');
        }
        
        // Redirect ke halaman pembayaran DP
        return redirect()->route('custom-orders.payment', $nomorCustomOrder);
    }

    public function payment($nomorCustomOrder)
    {
        $customOrder = CustomOrder::with(['jenis', 'pelanggan', 'payment'])
            ->where('nomor_custom_order', $nomorCustomOrder)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
            
        if ($customOrder->status !== 'approved' || $customOrder->isDpPaid()) {
            return redirect()->route('custom-orders.show', $nomorCustomOrder)
                ->with('error', 'Pembayaran tidak dapat dilakukan!');
        }
        
        // Generate or refresh Midtrans snap token if needed
        if (!$customOrder->payment || !$customOrder->payment->snap_token) {
            $this->generateCustomOrderPayment($customOrder);
        }
        
        return view('customer.custom-orders.payment', compact('customOrder'));
    }
    
    private function generateCustomOrderPayment($customOrder)
    {
        try {
            // Create payment record if not exists
            if (!$customOrder->payment) {
                $payment = Pembayaran::create([
                    'id_custom_order' => $customOrder->id_custom_order,
                    'jumlah_bayar' => $customOrder->dp_amount,
                    'status_bayar' => 'pending',
                ]);
                $customOrder->load('payment');
            }
            
            // Generate Midtrans snap token for custom order
            $snapToken = $this->midtransService->createCustomOrderTransaction($customOrder);
            
            $customOrder->payment->update([
                'snap_token' => $snapToken,
            ]);
            
            Log::info('Custom order payment token generated: ' . $customOrder->nomor_custom_order);
            
        } catch (\Exception $e) {
            Log::error('Failed to generate custom order payment: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function processPayment(Request $request, $nomorCustomOrder)
    {
        $customOrder = CustomOrder::with(['payment'])
            ->where('nomor_custom_order', $nomorCustomOrder)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
            
        if ($customOrder->status !== 'approved' || $customOrder->isDpPaid()) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak dapat dilakukan!'
            ], 400);
        }
        
        // Ensure payment record exists with snap token
        if (!$customOrder->payment || !$customOrder->payment->snap_token) {
            $this->generateCustomOrderPayment($customOrder);
        }
        
        return response()->json([
            'success' => true,
            'snap_token' => $customOrder->payment->snap_token,
            'order_number' => $customOrder->nomor_custom_order,
            'message' => 'Token pembayaran berhasil dibuat!'
        ]);
    }
    
    public function paymentFinish(Request $request, $nomorCustomOrder)
    {
        $customOrder = CustomOrder::with(['payment'])
            ->where('nomor_custom_order', $nomorCustomOrder)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
            
        if (!$customOrder->payment) {
            return redirect()->route('custom-orders.show', $nomorCustomOrder)
                ->with('error', 'Data pembayaran tidak ditemukan!');
        }
        
        $payment = $customOrder->payment;
        
        // Update payment status to paid (simplified)
        if ($payment->status_bayar !== 'paid') {
            $payment->update([
                'status_bayar' => 'paid',
                'tanggal_bayar' => now(),
            ]);
            $customOrder->update(['status' => 'in_production']);
        }
        
        $message = $payment->status_bayar == 'paid' 
            ? 'Pembayaran DP berhasil! Custom order Anda akan segera diproduksi.' 
            : 'Terima kasih! Pembayaran DP Anda sedang diverifikasi.';
        
        return redirect()->route('custom-orders.show', $nomorCustomOrder)
            ->with('success', $message);
    }
}