<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use App\Models\ProductVariant;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Get customers
        $customers = User::where('role', 'customer')->get();
        
        if ($customers->isEmpty()) {
            $this->command->warn('No customers found. Skipping order seeding.');
            return;
        }

        // Get some variants
        $variants = ProductVariant::with('product')->where('is_active', true)->get();
        
        if ($variants->isEmpty()) {
            $this->command->warn('No product variants found. Skipping order seeding.');
            return;
        }

        $statuses = ['pending', 'paid', 'processing', 'shipped', 'delivered'];
        
        // Get existing order count to continue numbering
        $existingCount = Order::count();
        
        // Create 10 sample orders
        foreach (range(1, 10) as $index) {
            $customer = $customers->random();
            $status = $statuses[array_rand($statuses)];
            
            // Select 1-3 random variants
            $orderVariants = $variants->random(rand(1, 3));
            
            $subtotal = 0;
            $totalWeight = 0;
            
            // Calculate subtotal and weight
            foreach ($orderVariants as $variant) {
                $quantity = rand(1, 2);
                $subtotal += $variant->price * $quantity;
                $totalWeight += $variant->weight * $quantity;
            }
            
            $shippingCost = rand(20000, 50000);
            $totalAmount = $subtotal + $shippingCost;
            
            // Create order
            $order = Order::create([
                'user_id' => $customer->id,
                'order_number' => 'ORD-' . date('Ymd') . '-' . str_pad($existingCount + $index, 4, '0', STR_PAD_LEFT),
                'status' => $status == 'paid' ? 'confirmed' : $status,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                
                // Customer info
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->phone ?? '081234567890',
                
                // Shipping info
                'shipping_address' => 'Jl. Raya Bebandem No. ' . rand(1, 100) . ', Karangasem',
                'shipping_city' => 'Karangasem',
                'shipping_province' => 'Bali',
                'shipping_postal_code' => '80861',
                'courier_service' => strtoupper(['jne', 'tiki', 'pos'][array_rand(['jne', 'tiki', 'pos'])]),
                'courier_type' => ['REG', 'YES', 'OKE'][array_rand(['REG', 'YES', 'OKE'])],
                'estimated_delivery' => rand(2, 5) . '-' . rand(3, 7) . ' hari',
                
                // Tracking
                'tracking_number' => $status == 'shipped' || $status == 'delivered' ? 'TRK' . rand(100000000, 999999999) : null,
                
                // Timestamps
                'shipped_at' => $status == 'shipped' || $status == 'delivered' ? now()->subDays(rand(1, 3)) : null,
                'delivered_at' => $status == 'delivered' ? now()->subDays(rand(0, 2)) : null,
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
            
            // Create order items
            foreach ($orderVariants as $variant) {
                $quantity = rand(1, 2);
                $price = $variant->price;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'variant_name' => $variant->name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $price * $quantity,
                ]);
            }
            
            // Create payment
            if ($status != 'pending') {
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => ['bank_transfer', 'credit_card', 'gopay', 'shopeepay'][array_rand(['bank_transfer', 'credit_card', 'gopay', 'shopeepay'])],
                    'amount' => $totalAmount,
                    'payment_status' => 'paid',
                    'midtrans_transaction_id' => 'TRX' . rand(100000000, 999999999),
                    'paid_at' => $order->created_at->addMinutes(rand(5, 60)),
                ]);
            } else {
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'bank_transfer',
                    'amount' => $totalAmount,
                    'payment_status' => 'pending',
                    'midtrans_transaction_id' => 'TRX' . rand(100000000, 999999999),
                ]);
            }
        }
        
        $this->command->info('✅ Orders seeded successfully!');
    }
}
