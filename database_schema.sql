-- Pelangi Tradisional Weaving Database Schema
-- Sistem Informasi Penjualan Kain Songket

-- Categories Table
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    image VARCHAR(255),
    parent_id BIGINT UNSIGNED NULL,
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Products Table
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    short_description TEXT,
    base_price DECIMAL(12,2) NOT NULL,
    weight DECIMAL(8,2) NOT NULL, -- dalam gram
    sku VARCHAR(100) UNIQUE,
    meta_title VARCHAR(255),
    meta_description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Product Images Table
CREATE TABLE product_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255),
    is_primary BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Product Variants Table
CREATE TABLE product_variants (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL, -- contoh: "Gold - M - Emas"
    color VARCHAR(100) NOT NULL, -- Gold, Silver, Merah, dll
    size VARCHAR(100) NOT NULL, -- S (2x1m), M (2.5x1m), L (3x1m)
    thread_type VARCHAR(100) NOT NULL, -- Emas, Silver, Katun, Sutra
    price DECIMAL(12,2) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    sku VARCHAR(100) UNIQUE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Shopping Cart Table
CREATE TABLE carts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    product_variant_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (user_id, product_variant_id)
);

-- Orders Table
CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    
    -- Customer Info
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    
    -- Shipping Address
    shipping_address TEXT NOT NULL,
    shipping_city VARCHAR(100) NOT NULL,
    shipping_province VARCHAR(100) NOT NULL,
    shipping_postal_code VARCHAR(10) NOT NULL,
    
    -- Pricing
    subtotal DECIMAL(12,2) NOT NULL,
    shipping_cost DECIMAL(12,2) NOT NULL DEFAULT 0,
    tax_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    total_amount DECIMAL(12,2) NOT NULL,
    
    -- Shipping Info
    courier_service VARCHAR(100), -- JNE, TIKI, POS
    courier_type VARCHAR(100), -- REG, OKE, YES
    estimated_delivery VARCHAR(50),
    tracking_number VARCHAR(100),
    
    -- Timestamps
    shipped_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order Items Table
CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_variant_id BIGINT UNSIGNED NOT NULL,
    product_name VARCHAR(255) NOT NULL, -- snapshot
    variant_name VARCHAR(255) NOT NULL, -- snapshot
    price DECIMAL(12,2) NOT NULL, -- snapshot
    quantity INT NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_variant_id) REFERENCES product_variants(id) ON DELETE CASCADE
);

-- Payments Table
CREATE TABLE payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    payment_method VARCHAR(100) NOT NULL, -- credit_card, bank_transfer, ewallet, etc
    payment_status ENUM('pending', 'paid', 'failed', 'cancelled', 'refunded') DEFAULT 'pending',
    
    -- Midtrans Integration
    midtrans_order_id VARCHAR(255),
    midtrans_transaction_id VARCHAR(255),
    midtrans_payment_type VARCHAR(100),
    midtrans_gross_amount DECIMAL(12,2),
    midtrans_transaction_status VARCHAR(100),
    midtrans_fraud_status VARCHAR(100),
    
    -- Payment Details
    amount DECIMAL(12,2) NOT NULL,
    paid_at TIMESTAMP NULL,
    expired_at TIMESTAMP NULL,
    
    -- Raw Response
    midtrans_response JSON,
    
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- User Addresses Table (untuk multiple alamat)
CREATE TABLE user_addresses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    label VARCHAR(100) NOT NULL, -- Rumah, Kantor, dll
    recipient_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    province VARCHAR(100) NOT NULL,
    postal_code VARCHAR(10) NOT NULL,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Sample Data untuk Development
INSERT INTO categories (name, slug, description, is_active) VALUES
('Songket Cendana', 'songket-cendana', 'Koleksi songket cendana dengan motif tradisional dan benang emas berkualitas tinggi', TRUE),
('Songket Banyumas', 'songket-banyumas', 'Songket khas Banyumas dengan corak semi tradisional', TRUE),
('Seledang Songket', 'seledang-songket', 'Seledang songket dengan motif tradisional yang elegan', TRUE),
('Songket Jembrena', 'songket-jembrena', 'Songket Jembrena dengan keindahan motif khas daerah', TRUE),
('Songket Seseh', 'songket-seseh', 'Songket Seseh klasik dengan nilai seni tinggi', TRUE);

-- Sample Products
INSERT INTO products (category_id, name, slug, description, base_price, weight, sku, is_active, is_featured) VALUES
(1, 'Songket Cendana', 'songket-cendana', 'Songket cendana dengan motif tradisional dan benang emas berkualitas tinggi. Dibuat dengan teknik tenun tradisional yang telah diwariskan turun temurun.', 1800000.00, 800.00, 'SC-001', TRUE, TRUE),
(2, 'Songket Banyumas Semi', 'songket-banyumas-semi', 'Songket Banyumas dengan corak semi tradisional yang memadukan keindahan klasik dengan sentuhan modern.', 2000000.00, 750.00, 'SB-001', TRUE, TRUE),
(3, 'Seledang Songket', 'seledang-songket', 'Seledang songket dengan motif tradisional yang elegan, cocok untuk acara formal dan pernikahan.', 800000.00, 400.00, 'SS-001', TRUE, FALSE),
(4, 'Songket Jembrena', 'songket-jembrena', 'Songket Jembrena dengan keindahan motif khas daerah yang memukau dan detail yang halus.', 1200000.00, 650.00, 'SJ-001', TRUE, FALSE),
(5, 'Songket Seseh Klasik', 'songket-seseh-klasik', 'Songket Seseh klasik dengan nilai seni tinggi dan keindahan yang tak lekang oleh waktu.', 2650000.00, 900.00, 'SSK-001', TRUE, TRUE);