# 📝 PANDUAN UPDATE VIEWS

Panduan lengkap untuk menyesuaikan semua Blade views dengan nama kolom database baru.

---

## 🔍 MAPPING KOLOM

### Category
```blade
<!-- LAMA -->
{{ $category->name }}
{{ $category->description }}
{{ $category->is_active }}

<!-- BARU -->
{{ $category->nama_kategori }}
{{ $category->deskripsi }}
{{ $category->status }} <!-- jika ada -->
```

### Product
```blade
<!-- LAMA -->
{{ $product->name }}
{{ $product->description }}
{{ $product->base_price }}
{{ $product->weight }}
{{ $product->is_active }}
{{ $product->category->name }}

<!-- BARU -->
{{ $product->nama_produk }}
{{ $product->deskripsi }}
{{ $product->harga }}
{{ $product->berat }}
{{ $product->status }}
{{ $product->category->nama_kategori }}
```

### Product Variant
```blade
<!-- LAMA -->
{{ $variant->name }}
{{ $variant->color }}
{{ $variant->size }}
{{ $variant->thread_type }}
{{ $variant->price }}
{{ $variant->stock_quantity }}
{{ $variant->weight }}
{{ $variant->sku }}
{{ $variant->is_active }}

<!-- BARU -->
{{ $variant->nama_varian }}
{{ $variant->warna }}
{{ $variant->ukuran }}
{{ $variant->jenis_benang }}
{{ $variant->harga }}
{{ $variant->stok }}
{{ $variant->berat }}
{{ $variant->kode_varian }}
{{ $variant->status }}
```

### Product Image
```blade
<!-- LAMA -->
{{ $image->image_path }}
{{ $image->alt_text }}

<!-- BARU -->
{{ $image->path }}
<!-- alt_text tidak ada di migration baru -->
```

### Cart
```blade
<!-- LAMA -->
{{ $cart->user_id }}
{{ $cart->product_id }}
{{ $cart->product_variant_id }}
{{ $cart->quantity }}
{{ $cart->user->name }}

<!-- BARU -->
{{ $cart->id_pelanggan }}
{{ $cart->id_produk }}
{{ $cart->id_varian }}
{{ $cart->jumlah }}
{{ $cart->pelanggan->nama }}
```

### Order
```blade
<!-- LAMA -->
{{ $order->user_id }}
{{ $order->order_number }}
{{ $order->status }}
{{ $order->customer_name }}
{{ $order->customer_email }}
{{ $order->customer_phone }}
{{ $order->shipping_address }}
{{ $order->shipping_city }}
{{ $order->shipping_province }}
{{ $order->shipping_postal_code }}
{{ $order->subtotal }}
{{ $order->shipping_cost }}
{{ $order->tax_amount }}
{{ $order->total_amount }}
{{ $order->notes }}
{{ $order->user->name }}

<!-- BARU -->
{{ $order->id_pelanggan }}
{{ $order->nomor_invoice }}
{{ $order->status_pesanan }}
{{ $order->pelanggan->nama }}
{{ $order->pelanggan->email }}
{{ $order->pelanggan->telepon }}
{{ $order->pengiriman->alamat_pengiriman }}
<!-- city, province, postal_code ada di pelanggan atau pengiriman -->
{{ $order->subtotal }}
{{ $order->ongkir }}
<!-- tax_amount tidak ada -->
{{ $order->total_bayar }}
{{ $order->catatan }}
{{ $order->pelanggan->nama }}
```

### Order Item
```blade
<!-- LAMA -->
{{ $item->order_id }}
{{ $item->product_id }}
{{ $item->product_variant_id }}
{{ $item->product_name }}
{{ $item->variant_name }}
{{ $item->quantity }}
{{ $item->price }}
{{ $item->subtotal }}

<!-- BARU -->
{{ $item->id_pesanan }}
{{ $item->id_produk }}
{{ $item->id_varian }}
{{ $item->product->nama_produk }}
{{ $item->productVariant->nama_varian }}
{{ $item->jumlah }}
{{ $item->harga_satuan }}
{{ $item->subtotal }}
```

### Payment
```blade
<!-- LAMA -->
{{ $payment->order_id }}
{{ $payment->payment_method }}
{{ $payment->payment_status }}
{{ $payment->amount }}
{{ $payment->midtrans_order_id }}
{{ $payment->snap_token }}
{{ $payment->midtrans_payment_type }}
{{ $payment->midtrans_transaction_status }}
{{ $payment->midtrans_fraud_status }}
{{ $payment->paid_at }}

<!-- BARU -->
{{ $payment->id_pesanan }}
<!-- payment_method tidak ada -->
{{ $payment->status_pembayaran }}
<!-- amount tidak ada, gunakan order->total_bayar -->
{{ $payment->midtrans_order_id }}
{{ $payment->snap_token }}
{{ $payment->tipe_pembayaran }}
<!-- transaction_status tidak ada -->
{{ $payment->fraud_status }}
{{ $payment->waktu_settlement }}
```

### Pengiriman
```blade
<!-- LAMA -->
{{ $pengiriman->order_id }}
{{ $pengiriman->kurir }}
{{ $pengiriman->layanan }}
{{ $pengiriman->ongkir }}
{{ $pengiriman->estimasi_pengiriman }}
{{ $pengiriman->alamat_pengiriman }}
{{ $pengiriman->no_resi }}
{{ $pengiriman->status_pengiriman }}

<!-- BARU -->
{{ $pengiriman->id_pesanan }}
{{ $pengiriman->id_kota_asal }}
{{ $pengiriman->id_kota_tujuan }}
{{ $pengiriman->kurir }}
{{ $pengiriman->layanan }}
{{ $pengiriman->ongkir }}
{{ $pengiriman->estimasi_pengiriman }}
{{ $pengiriman->alamat_pengiriman }}
{{ $pengiriman->no_resi }}
{{ $pengiriman->status_pengiriman }}
{{ $pengiriman->tanggal_kirim }}
{{ $pengiriman->tanggal_terima }}
```

### User/Pelanggan
```blade
<!-- LAMA -->
{{ $user->name }}
{{ $user->email }}
{{ $user->phone }}
{{ $user->role }}

<!-- BARU (Pelanggan) -->
{{ $pelanggan->nama }}
{{ $pelanggan->email }}
{{ $pelanggan->telepon }}
{{ $pelanggan->alamat }}
{{ $pelanggan->id_kota }}
{{ $pelanggan->id_provinsi }}
{{ $pelanggan->kode_pos }}
```

### Admin
```blade
<!-- LAMA -->
{{ $admin->name }}
{{ $admin->email }}
{{ $admin->role }}

<!-- BARU -->
{{ $admin->nama }}
{{ $admin->email }}
{{ $admin->role }}
{{ $admin->last_login }}
```

---

## 🎯 STATUS VALUES

### Product Status
```blade
<!-- LAMA -->
@if($product->is_active)
    <span class="badge badge-success">Active</span>
@else
    <span class="badge badge-danger">Inactive</span>
@endif

<!-- BARU -->
@if($product->status === 'aktif')
    <span class="badge badge-success">Aktif</span>
@else
    <span class="badge badge-danger">Nonaktif</span>
@endif
```

### Variant Status
```blade
<!-- LAMA -->
@if($variant->is_active)
    <span class="badge badge-success">Active</span>
@else
    <span class="badge badge-danger">Inactive</span>
@endif

<!-- BARU -->
@if($variant->status === 'tersedia')
    <span class="badge badge-success">Tersedia</span>
@else
    <span class="badge badge-danger">Habis</span>
@endif
```

### Order Status
```blade
<!-- LAMA -->
@switch($order->status)
    @case('pending')
        <span class="badge badge-warning">Pending</span>
        @break
    @case('confirmed')
        <span class="badge badge-info">Confirmed</span>
        @break
    @case('processing')
        <span class="badge badge-primary">Processing</span>
        @break
    @case('shipped')
        <span class="badge badge-info">Shipped</span>
        @break
    @case('delivered')
        <span class="badge badge-success">Delivered</span>
        @break
    @case('cancelled')
        <span class="badge badge-danger">Cancelled</span>
        @break
@endswitch

<!-- BARU -->
@switch($order->status_pesanan)
    @case('baru')
        <span class="badge badge-warning">Baru</span>
        @break
    @case('diproses')
        <span class="badge badge-primary">Diproses</span>
        @break
    @case('dikirim')
        <span class="badge badge-info">Dikirim</span>
        @break
    @case('selesai')
        <span class="badge badge-success">Selesai</span>
        @break
    @case('batal')
        <span class="badge badge-danger">Batal</span>
        @break
@endswitch
```

### Payment Status
```blade
<!-- LAMA -->
@switch($payment->payment_status)
    @case('pending')
        <span class="badge badge-warning">Pending</span>
        @break
    @case('paid')
        <span class="badge badge-success">Paid</span>
        @break
    @case('failed')
        <span class="badge badge-danger">Failed</span>
        @break
@endswitch

<!-- BARU -->
@switch($payment->status_pembayaran)
    @case('unpaid')
        <span class="badge badge-secondary">Belum Bayar</span>
        @break
    @case('pending')
        <span class="badge badge-warning">Pending</span>
        @break
    @case('paid')
        <span class="badge badge-success">Lunas</span>
        @break
    @case('cancel')
        <span class="badge badge-danger">Dibatalkan</span>
        @break
    @case('expire')
        <span class="badge badge-danger">Kadaluarsa</span>
        @break
    @case('failure')
        <span class="badge badge-danger">Gagal</span>
        @break
@endswitch
```

### Shipping Status
```blade
<!-- BARU -->
@switch($pengiriman->status_pengiriman)
    @case('menunggu')
        <span class="badge badge-secondary">Menunggu</span>
        @break
    @case('dalam_perjalanan')
        <span class="badge badge-info">Dalam Perjalanan</span>
        @break
    @case('sampai')
        <span class="badge badge-success">Sampai</span>
        @break
@endswitch
```

---

## 🔄 FORM INPUTS

### Product Form
```blade
<!-- LAMA -->
<input type="text" name="name" value="{{ old('name', $product->name) }}">
<input type="number" name="base_price" value="{{ old('base_price', $product->base_price) }}">
<input type="number" name="weight" value="{{ old('weight', $product->weight) }}">
<select name="category_id">
    @foreach($categories as $category)
        <option value="{{ $category->id }}">{{ $category->name }}</option>
    @endforeach
</select>

<!-- BARU -->
<input type="text" name="nama_produk" value="{{ old('nama_produk', $product->nama_produk) }}">
<input type="number" name="harga" value="{{ old('harga', $product->harga) }}">
<input type="number" name="berat" value="{{ old('berat', $product->berat) }}">
<select name="id_kategori">
    @foreach($categories as $category)
        <option value="{{ $category->id_kategori }}">{{ $category->nama_kategori }}</option>
    @endforeach
</select>
```

### Variant Form
```blade
<!-- LAMA -->
<input type="text" name="name" value="{{ old('name', $variant->name) }}">
<input type="text" name="color" value="{{ old('color', $variant->color) }}">
<input type="text" name="size" value="{{ old('size', $variant->size) }}">
<input type="text" name="thread_type" value="{{ old('thread_type', $variant->thread_type) }}">
<input type="number" name="price" value="{{ old('price', $variant->price) }}">
<input type="number" name="stock_quantity" value="{{ old('stock_quantity', $variant->stock_quantity) }}">

<!-- BARU -->
<input type="text" name="nama_varian" value="{{ old('nama_varian', $variant->nama_varian) }}">
<input type="text" name="warna" value="{{ old('warna', $variant->warna) }}">
<input type="text" name="ukuran" value="{{ old('ukuran', $variant->ukuran) }}">
<input type="text" name="jenis_benang" value="{{ old('jenis_benang', $variant->jenis_benang) }}">
<input type="number" name="harga" value="{{ old('harga', $variant->harga) }}">
<input type="number" name="stok" value="{{ old('stok', $variant->stok) }}">
```

### Cart Form
```blade
<!-- LAMA -->
<input type="hidden" name="product_variant_id" value="{{ $variant->id }}">
<input type="number" name="quantity" value="1" min="1" max="{{ $variant->stock_quantity }}">

<!-- BARU -->
<input type="hidden" name="id_produk" value="{{ $product->id_produk }}">
<input type="hidden" name="id_varian" value="{{ $variant->id_varian }}">
<input type="number" name="jumlah" value="1" min="1" max="{{ $variant->stok }}">
```

---

## 🔍 SEARCH & FILTER

### Product Search
```blade
<!-- LAMA -->
<form action="{{ route('products.index') }}" method="GET">
    <input type="text" name="search" placeholder="Search products...">
    <select name="category">
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
</form>

<!-- BARU -->
<form action="{{ route('products.index') }}" method="GET">
    <input type="text" name="search" placeholder="Cari produk...">
    <select name="category">
        @foreach($categories as $category)
            <option value="{{ $category->id_kategori }}">{{ $category->nama_kategori }}</option>
        @endforeach
    </select>
</form>
```

---

## 📋 CHECKLIST UPDATE

### Admin Views
- [ ] `admin/dashboard.blade.php`
- [ ] `admin/categories/index.blade.php`
- [ ] `admin/categories/create.blade.php`
- [ ] `admin/categories/edit.blade.php`
- [ ] `admin/categories/show.blade.php`
- [ ] `admin/products/index.blade.php`
- [ ] `admin/products/create.blade.php`
- [ ] `admin/products/edit.blade.php`
- [ ] `admin/products/show.blade.php`
- [ ] `admin/orders/index.blade.php`
- [ ] `admin/orders/show.blade.php`
- [ ] `admin/orders/invoice.blade.php`

### Customer Views
- [ ] `customer/home.blade.php`
- [ ] `customer/products/index.blade.php`
- [ ] `customer/products/show.blade.php`
- [ ] `customer/cart/index.blade.php`
- [ ] `customer/checkout/index.blade.php`
- [ ] `customer/orders/index.blade.php`
- [ ] `customer/orders/show.blade.php`
- [ ] `customer/payment/show.blade.php`

### Auth Views
- [ ] `auth/login.blade.php`
- [ ] `auth/register.blade.php`
- [ ] `auth/admin-login.blade.php`

### Layout Views
- [ ] `layouts/admin.blade.php`
- [ ] `layouts/customer.blade.php`
- [ ] `layouts/app.blade.php`

---

## 💡 TIPS

1. **Find & Replace**: Gunakan editor dengan fitur find & replace untuk mempercepat
2. **Test Incremental**: Update dan test satu view pada satu waktu
3. **Backup**: Backup views sebelum melakukan perubahan
4. **Check Relations**: Pastikan relasi model sudah benar
5. **Validation**: Update validation rules di form requests jika ada

---

## 🐛 COMMON ERRORS

### Error: Undefined property
```
Trying to get property 'name' of non-object
```
**Fix**: Ganti `name` dengan `nama_produk` / `nama_kategori` / dll

### Error: Column not found
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'name'
```
**Fix**: Update query atau form input dengan nama kolom baru

### Error: Undefined index
```
Undefined index: product_id
```
**Fix**: Ganti `product_id` dengan `id_produk`

---

Setelah semua views diupdate, test semua fitur untuk memastikan tidak ada error!
