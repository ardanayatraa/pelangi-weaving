# Midtrans & RajaOngkir Integration Guide

## 📦 Setup

### 1. Install Dependencies
```bash
composer require midtrans/midtrans-php
```

### 2. Get API Keys

#### Midtrans (Sandbox)
1. Daftar di https://dashboard.sandbox.midtrans.com/
2. Login dan ambil credentials:
   - **Merchant ID**: Di Settings > Access Keys
   - **Client Key**: SB-Mid-client-xxxxx
   - **Server Key**: SB-Mid-server-xxxxx

#### RajaOngkir (Starter)
1. Daftar di https://rajaongkir.com/
2. Pilih paket **Starter** (gratis)
3. Ambil **API Key** dari dashboard

### 3. Update .env
```env
# Midtrans Configuration
MIDTRANS_MERCHANT_ID=G123456789
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxx
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxx
MIDTRANS_IS_PRODUCTION=false

# RajaOngkir Configuration
RAJAONGKIR_API_KEY=your-api-key-here
RAJAONGKIR_BASE_URL=https://api.rajaongkir.com/starter
```

## 🚀 Features Implemented

### 1. RajaOngkir Integration
- ✅ Get provinces list
- ✅ Get cities by province
- ✅ Calculate shipping cost (JNE, TIKI, POS)
- ✅ Real-time cost calculation
- ✅ Multiple courier options

### 2. Midtrans Integration
- ✅ Snap payment gateway
- ✅ Multiple payment methods (Credit Card, Bank Transfer, E-Wallet)
- ✅ Payment notification handler
- ✅ Transaction status check
- ✅ Automatic order status update

## 📱 Responsive Design

### Breakpoints
- **Mobile**: < 640px
- **Tablet**: 640px - 1024px
- **Desktop**: > 1024px

### Features
- ✅ Mobile-first approach
- ✅ Touch-friendly buttons
- ✅ Collapsible sections on mobile
- ✅ Optimized forms for mobile
- ✅ Responsive images
- ✅ Adaptive navigation

## 🎨 UI Components

### Checkout Page
```
┌─────────────────────────────────────┐
│  Customer Info (Form)               │
├─────────────────────────────────────┤
│  Shipping Address (Form + API)     │
├─────────────────────────────────────┤
│  Shipping Method (RajaOngkir API)  │
├─────────────────────────────────────┤
│  Order Summary (Sticky Sidebar)    │
└─────────────────────────────────────┘
```

### Payment Flow
```
Cart → Checkout → Create Order → Midtrans Payment → Success/Pending
```

## 🔧 API Endpoints

### RajaOngkir
```php
GET /api/rajaongkir/provinces
GET /api/rajaongkir/cities?province_id={id}
POST /api/rajaongkir/cost
```

### Midtrans
```php
POST /payment/notification (Webhook)
GET /orders/{order}/payment
```

## 📝 Usage Examples

### Get Shipping Cost
```javascript
fetch('/api/rajaongkir/cost', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
        destination: cityId,
        weight: totalWeight,
        courier: 'jne'
    })
})
```

### Process Payment
```javascript
snap.pay(snapToken, {
    onSuccess: function(result) {
        window.location.href = '/orders/' + orderId;
    },
    onPending: function(result) {
        window.location.href = '/orders/' + orderId;
    },
    onError: function(result) {
        alert('Payment failed!');
    }
});
```

## 🔐 Security

- ✅ CSRF Protection
- ✅ Input validation
- ✅ SQL injection prevention
- ✅ XSS protection
- ✅ Secure API keys (env)
- ✅ HTTPS required for production

## 🧪 Testing

### Midtrans Test Cards
```
Card Number: 4811 1111 1111 1114
CVV: 123
Exp: 01/25
OTP: 112233
```

### RajaOngkir Test
```
Origin: Jakarta (city_id: 152)
Destination: Bandung (city_id: 23)
Weight: 1000 (gram)
Courier: jne, tiki, pos
```

## 📊 Database Schema

### Orders Table
- snap_token (for Midtrans)
- payment_status
- shipping_cost
- courier_service
- courier_type

### Payments Table
- midtrans_order_id
- midtrans_transaction_id
- payment_type
- transaction_status

## 🎯 Next Steps

1. **Test Integration**
   - Test dengan sandbox credentials
   - Verify payment flow
   - Check shipping calculation

2. **Production Setup**
   - Get production API keys
   - Update .env
   - Test on production

3. **Monitoring**
   - Setup logging
   - Monitor transactions
   - Track errors

## 📞 Support

- **Midtrans**: https://docs.midtrans.com
- **RajaOngkir**: https://rajaongkir.com/dokumentasi
- **Laravel**: https://laravel.com/docs

---

**Note**: Pastikan semua API keys sudah diisi dengan benar di file `.env` sebelum testing!
