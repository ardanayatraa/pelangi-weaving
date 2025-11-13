#!/bin/bash

# Pelangi Weaving - Development Helper Script

echo "🌈 Pelangi Weaving - Development Helper"
echo "========================================"
echo ""

# Function to show menu
show_menu() {
    echo "Pilih aksi:"
    echo "1. Setup Fresh (migrate:fresh + seed)"
    echo "2. Clear All Cache"
    echo "3. Run Server"
    echo "4. Run Tests"
    echo "5. Check Routes"
    echo "6. Check Models"
    echo "7. Storage Link"
    echo "8. Optimize"
    echo "9. Show Logs"
    echo "0. Exit"
    echo ""
}

# Main loop
while true; do
    show_menu
    read -p "Pilihan (0-9): " choice
    echo ""
    
    case $choice in
        1)
            echo "🔄 Running migrate:fresh --seed..."
            php artisan migrate:fresh --seed
            echo "✅ Done!"
            ;;
        2)
            echo "🧹 Clearing all cache..."
            php artisan cache:clear
            php artisan config:clear
            php artisan route:clear
            php artisan view:clear
            echo "✅ Done!"
            ;;
        3)
            echo "🚀 Starting server..."
            php artisan serve
            ;;
        4)
            echo "🧪 Running tests..."
            php artisan test
            ;;
        5)
            echo "📋 Checking routes..."
            php artisan route:list
            ;;
        6)
            echo "📦 Available models:"
            echo "- Admin"
            echo "- Pelanggan"
            echo "- Category"
            echo "- Product"
            echo "- ProductVariant"
            echo "- ProductImage"
            echo "- Cart"
            echo "- Order"
            echo "- OrderItem"
            echo "- Payment"
            echo "- Pengiriman"
            echo ""
            read -p "Model name to inspect: " model
            php artisan model:show $model
            ;;
        7)
            echo "🔗 Creating storage link..."
            php artisan storage:link
            echo "✅ Done!"
            ;;
        8)
            echo "⚡ Optimizing..."
            php artisan optimize
            echo "✅ Done!"
            ;;
        9)
            echo "📄 Showing last 50 lines of log..."
            tail -n 50 storage/logs/laravel.log
            ;;
        0)
            echo "👋 Goodbye!"
            exit 0
            ;;
        *)
            echo "❌ Invalid choice!"
            ;;
    esac
    
    echo ""
    read -p "Press Enter to continue..."
    clear
done
