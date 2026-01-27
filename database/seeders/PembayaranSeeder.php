<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pesanan;
use App\Models\Pembayaran;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        // Update existing payments with correct data
        $payments = Pembayaran::with('order')->get();
        
        foreach ($payments as $payment) {
            if ($payment->order) {
                $status = fake()->randomElement(['paid', 'pending', 'unpaid']);
                
                $payment->update([
                    'jumlah_bayar' => $payment->order->total_bayar,
                    'status_bayar' => $status,
                    'tanggal_bayar' => $status === 'paid' ? fake()->dateTimeBetween($payment->order->created_at, 'now') : null,
                    'snap_token' => $status !== 'paid' ? 'snap_' . fake()->uuid() : null,
                ]);
                
                echo "Updated payment for order {$payment->order->nomor_invoice} - Status: {$status}, Amount: Rp " . number_format($payment->order->total_bayar, 0, ',', '.') . "\n";
            }
        }
        
        // Create payments for orders that don't have payment yet
        $ordersWithoutPayment = Pesanan::whereDoesntHave('payment')->get();
        
        foreach ($ordersWithoutPayment as $order) {
            $status = fake()->randomElement(['paid', 'pending', 'unpaid']);
            
            Pembayaran::create([
                'id_pesanan' => $order->id_pesanan,
                'jumlah_bayar' => $order->total_bayar,
                'status_bayar' => $status,
                'tanggal_bayar' => $status === 'paid' ? fake()->dateTimeBetween($order->created_at, 'now') : null,
                'snap_token' => $status !== 'paid' ? 'snap_' . fake()->uuid() : null,
            ]);
            
            echo "Created payment for order {$order->nomor_invoice} - Status: {$status}, Amount: Rp " . number_format($order->total_bayar, 0, ',', '.') . "\n";
        }
        
        echo "Payment seeder completed!\n";
    }
}