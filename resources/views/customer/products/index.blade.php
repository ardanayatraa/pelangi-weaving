@extends('layouts.customer')

@section('title', 'Produk')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Semua Produk</h2>
    
    <div class="row g-4">
        @forelse($products as $product)
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                @if($product->images->first())
                <img src="{{ Storage::url($product->images->first()->path) }}" class="card-img-top" alt="{{ $product->nama_produk }}">
                @else
                <div class="bg-light" style="height: 200px;"></div>
                @endif
                <div class="card-body">
                    <span class="badge bg-secondary mb-2">{{ $product->category->nama_kategori }}</span>
                    <h6 class="card-title">{{ $product->nama_produk }}</h6>
                    <p class="text-primary fw-bold mb-2">{!! $product->getFormattedPrice() !!}</p>
                    <p class="text-muted small mb-3">Stok: {{ $product->stok }}</p>
                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-sm btn-primary-custom w-100">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">Belum ada produk</div>
        </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
