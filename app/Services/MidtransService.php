<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createTransaction($order, $cartItems = null)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->nomor_invoice,
                'gross_amount' => (int) $order->total_bayar,
            ],
            'customer_details' => [
                'first_name' => $order->pelanggan->nama,
                'email' => $order->pelanggan->email,
                'phone' => $order->pelanggan->telepon,
            ],
            'item_details' => $this->getItemDetails($order),
            'callbacks' => [
                'finish' => route('payment.finish'),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create Midtrans transaction: ' . $e->getMessage());
        }
    }

    private function getItemDetails($order)
    {
        $items = [];

        foreach ($order->items as $item) {
            $productName = $item->product->nama_produk;
            if ($item->productVariant) {
                $productName .= ' - ' . $item->productVariant->nama_varian;
            }
            
            $items[] = [
                'id' => $item->id_varian ?? $item->id_produk,
                'price' => (int) $item->harga_satuan,
                'quantity' => $item->jumlah,
                'name' => $productName,
            ];
        }

        // Add shipping cost
        if ($order->ongkir > 0) {
            $items[] = [
                'id' => 'SHIPPING',
                'price' => (int) $order->ongkir,
                'quantity' => 1,
                'name' => 'Ongkos Kirim',
            ];
        }

        return $items;
    }

    public function createCustomOrderTransaction($customOrder)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $customOrder->nomor_custom_order,
                'gross_amount' => (int) $customOrder->dp_amount,
            ],
            'customer_details' => [
                'first_name' => $customOrder->pelanggan->nama,
                'email' => $customOrder->pelanggan->email,
                'phone' => $customOrder->pelanggan->telepon,
            ],
            'item_details' => [
                [
                    'id' => 'CUSTOM_DP_' . $customOrder->id_custom_order,
                    'price' => (int) $customOrder->dp_amount,
                    'quantity' => 1,
                    'name' => 'DP Custom Order - ' . $customOrder->nama_custom,
                ]
            ],
            'callbacks' => [
                'finish' => route('custom-orders.payment.finish', $customOrder->nomor_custom_order),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create custom order transaction: ' . $e->getMessage());
        }
    }

    public function getTransactionStatus($orderId)
    {
        try {
            return Transaction::status($orderId);
        } catch (\Exception $e) {
            throw new \Exception('Failed to get transaction status: ' . $e->getMessage());
        }
    }

    public function handleNotification($notificationData)
    {
        try {
            $notification = new \Midtrans\Notification($notificationData);
            return $notification;
        } catch (\Exception $e) {
            throw new \Exception('Failed to handle notification: ' . $e->getMessage());
        }
    }
}
