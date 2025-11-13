<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\VarianProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Keranjang::with(['product', 'productVariant.product'])
            ->where('id_pelanggan', Auth::id())
            ->get();
        
        $subtotal = $cartItems->sum(function($item) {
            $harga = $item->productVariant ? $item->productVariant->harga : $item->product->harga;
            return $item->jumlah * $harga;
        });
        
        return view('customer.cart.index', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'id_produk' => 'required|exists:produk,id_produk',
            'id_varian' => 'nullable|exists:varian_produk,id_varian',
            'jumlah' => 'required|integer|min:1',
        ]);
        
        // Check stock
        if ($validated['id_varian']) {
            $variant = VarianProduk::findOrFail($validated['id_varian']);
            if ($variant->stok < $validated['jumlah']) {
                return back()->with('error', 'Stok tidak mencukupi!');
            }
        }
        
        // If "Buy Now" - clear cart first and add only this item, then redirect to checkout
        if ($request->has('buy_now')) {
            Keranjang::where('id_pelanggan', Auth::id())->delete();
            
            Keranjang::create([
                'id_pelanggan' => Auth::id(),
                'id_produk' => $validated['id_produk'],
                'id_varian' => $validated['id_varian'],
                'jumlah' => $validated['jumlah'],
            ]);
            
            return redirect()->route('checkout.index');
        }
        
        // Normal "Add to Cart" flow
        $cartItem = Keranjang::where('id_pelanggan', Auth::id())
            ->where('id_produk', $validated['id_produk'])
            ->where('id_varian', $validated['id_varian'])
            ->first();
        
        if ($cartItem) {
            $newQuantity = $cartItem->jumlah + $validated['jumlah'];
            
            if ($validated['id_varian']) {
                $variant = VarianProduk::findOrFail($validated['id_varian']);
                if ($variant->stok < $newQuantity) {
                    return back()->with('error', 'Stok tidak mencukupi!');
                }
            }
            
            $cartItem->update(['jumlah' => $newQuantity]);
        } else {
            Keranjang::create([
                'id_pelanggan' => Auth::id(),
                'id_produk' => $validated['id_produk'],
                'id_varian' => $validated['id_varian'],
                'jumlah' => $validated['jumlah'],
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $cart = Keranjang::findOrFail($id);
        
        if ($cart->id_pelanggan != Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);
        
        // Check stock
        if ($cart->id_varian) {
            $variant = VarianProduk::findOrFail($cart->id_varian);
            if ($variant->stok < $validated['jumlah']) {
                return back()->with('error', 'Stok tidak mencukupi!');
            }
        }
        
        $cart->update($validated);
        
        return back()->with('success', 'Keranjang berhasil diupdate!');
    }

    public function remove($id)
    {
        $cart = Keranjang::findOrFail($id);
        
        if ($cart->id_pelanggan != Auth::id()) {
            abort(403);
        }
        
        $cart->delete();
        
        return back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    public function clear()
    {
        Keranjang::where('id_pelanggan', Auth::id())->delete();
        
        return back()->with('success', 'Keranjang berhasil dikosongkan!');
    }
}
