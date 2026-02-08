<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }
    public function index(Request $request)
    {
        // Get selected cart items from request (comma-separated IDs)
        $selectedIds = $request->input('items');
        
        if (!$selectedIds) {
            return redirect()->route('cart.index')
                ->with('error', 'Pilih produk yang ingin dibeli!');
        }
        
        $selectedIds = explode(',', $selectedIds);
        
        $cartItems = Keranjang::with(['product.variants', 'productVariant'])
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->whereIn('id_keranjang', $selectedIds)
            ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja kosong!');
        }
        
        // Check stock
        foreach ($cartItems as $item) {
            if ($item->productVariant && $item->productVariant->stok < $item->jumlah) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok {$item->productVariant->nama_varian} tidak mencukupi!");
            }
        }
        
        $subtotal = $cartItems->sum(function($item) {
            $harga = $item->productVariant ? $item->productVariant->harga : $item->product->harga;
            return $item->jumlah * $harga;
        });
        
        $pelanggan = Auth::guard('pelanggan')->user();
        
        // Get default address
        $alamatList = $pelanggan->getAlamatList();
        $defaultIndex = $pelanggan->alamat_default_index ?? 0;
        $defaultAlamat = $alamatList[$defaultIndex] ?? null;
        
        return view('customer.checkout.index', compact('cartItems', 'subtotal', 'pelanggan', 'defaultAlamat'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_province' => 'required|string',
            'shipping_postal_code' => 'required|string',
            'courier_service' => 'required|string|max:50',
            'courier_type' => 'required|string|max:50',
            'shipping_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'selected_items' => 'required|string', // comma-separated cart IDs
        ]);
        
        $pelangganId = Auth::guard('pelanggan')->id();
        
        // Get only selected items
        $selectedIds = explode(',', $validated['selected_items']);
        
        $cartItems = Keranjang::with(['productVariant', 'product.variants'])
            ->where('id_pelanggan', $pelangganId)
            ->whereIn('id_keranjang', $selectedIds)
            ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja kosong!');
        }
        
        DB::beginTransaction();
        
        try {
            $subtotal = $cartItems->sum(function($item) {
                $harga = $item->productVariant ? $item->productVariant->harga : $item->product->harga;
                return $item->jumlah * $harga;
            });
            
            $ongkir = $validated['shipping_cost'];
            
            $totalBayar = $subtotal + $ongkir;
            
            $nomorInvoice = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            
            $order = Pesanan::create([
                'id_pelanggan' => $pelangganId,
                'nomor_invoice' => $nomorInvoice,
                'tanggal_pesanan' => now(),
                'subtotal' => $subtotal,
                'ongkir' => $ongkir,
                'total_bayar' => $totalBayar,
                'status_pesanan' => 'baru',
                'catatan' => $validated['notes'],
            ]);
            
            foreach ($cartItems as $cartItem) {
                $harga = $cartItem->productVariant ? $cartItem->productVariant->harga : $cartItem->product->harga;
                
                DetailPesanan::create([
                    'id_pesanan' => $order->id_pesanan,
                    'id_produk' => $cartItem->id_produk,
                    'id_varian' => $cartItem->id_varian,
                    'jumlah' => $cartItem->jumlah,
                    'harga_satuan' => $harga,
                    'subtotal' => $cartItem->jumlah * $harga,
                ]);
                
                if ($cartItem->productVariant) {
                    $cartItem->productVariant->decrement('stok', $cartItem->jumlah);
                } else {
                    $cartItem->product->decrement('stok', $cartItem->jumlah);
                }
            }
            
            $fullAddress = $validated['shipping_address'] . ', ' . 
                          $validated['shipping_city'] . ', ' . 
                          $validated['shipping_province'] . ' ' . 
                          $validated['shipping_postal_code'];
            
            Pengiriman::create([
                'id_pesanan' => $order->id_pesanan,
                'kurir' => strtoupper($validated['courier_service']),
                'layanan' => strtoupper($validated['courier_type']),
                'ongkir' => $ongkir,
                'alamat_pengiriman' => $fullAddress,
                'status_pengiriman' => 'menunggu',
            ]);
            
            // Generate Midtrans Snap Token
            $snapToken = null;
            try {
                $snapToken = $this->midtransService->createTransaction($order);
                Log::info('Snap token generated for order: ' . $order->nomor_invoice);
            } catch (\Exception $e) {
                Log::error('Failed to generate snap token: ' . $e->getMessage());
                // Continue without snap token - can be generated later
            }
            
            // Create payment record with snap token
            Pembayaran::create([
                'id_pesanan' => $order->id_pesanan,
                'status_pembayaran' => 'unpaid',
                'status_bayar' => 'unpaid',
                'snap_token' => $snapToken,
            ]);
            
            DB::commit();
            
            // Delete only selected items from cart
            Keranjang::where('id_pelanggan', $pelangganId)
                ->whereIn('id_keranjang', $selectedIds)
                ->delete();
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken,
                    'order_number' => $order->nomor_invoice,
                    'message' => 'Pesanan berhasil dibuat!'
                ]);
            }
            
            return redirect()->route('payment.show', $order->nomor_invoice)
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Checkout error: ' . $e->getMessage());
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
