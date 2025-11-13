<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        
        $colors = [
            'Merah Marun',
            'Hijau Lumut', 
            'Biru Laut',
            'Kuning Emas',
            'Ungu Tua',
            'Coklat Tanah',
            'Putih Gading',
            'Hitam Pekat'
        ];
        
        $sizes = [
            '2 x 1 meter' => 0,
            '2.5 x 1.1 meter' => 250000,
            '3 x 1.2 meter' => 500000,
        ];
        
        $threadTypes = [
            'Benang Emas 24K' => 0,
            'Benang Perak' => -150000,
            'Benang Sutra' => -200000,
            'Benang Katun Premium' => -400000,
        ];

        $globalVariantCount = 0;
        
        foreach ($products as $product) {
            $variantCount = 0;
            
            $numColors = rand(3, 4);
            $numSizes = 2;
            
            $availableThreads = $product->harga > 2000000 
                ? array_slice(array_keys($threadTypes), 0, 2) 
                : array_slice(array_keys($threadTypes), 2, 2);
            
            foreach (array_slice($colors, 0, $numColors) as $color) {
                foreach (array_slice(array_keys($sizes), 0, $numSizes) as $size) {
                    foreach (array_slice($availableThreads, 0, 1) as $threadType) {
                        $variantCount++;
                        $globalVariantCount++;
                        
                        $priceAdjustment = $sizes[$size] + $threadTypes[$threadType];
                        $finalPrice = $product->harga + $priceAdjustment;
                        
                        $colorCode = strtoupper(substr(str_replace(' ', '', $color), 0, 3));
                        $sizeCode = str_replace(' x ', 'X', str_replace(' meter', '', $size));
                        $kodeVarian = 'PW-' . $product->id_produk . '-' . $colorCode . '-' . str_replace(' ', '', $sizeCode);
                        
                        $stok = $finalPrice > 5000000 ? rand(2, 5) : 
                                ($finalPrice > 2000000 ? rand(5, 12) : rand(10, 25));
                        
                        $weightAdjustment = $sizes[$size] > 0 ? ($sizes[$size] / 1000000) * $product->berat : 0;
                        $finalWeight = $product->berat + $weightAdjustment;
                        
                        ProductVariant::create([
                            'id_produk' => $product->id_produk,
                            'nama_varian' => "$color - $size",
                            'kode_varian' => $kodeVarian,
                            'warna' => $color,
                            'ukuran' => $size,
                            'jenis_benang' => $threadType,
                            'harga' => $finalPrice,
                            'stok' => $stok,
                            'berat' => $finalWeight,
                            'status' => 'tersedia',
                        ]);
                        
                        if ($variantCount >= 6) break 3;
                    }
                }
            }
        }

        echo "✅ Varian produk berhasil di-seed! Total: $globalVariantCount varian\n";
    }
}
