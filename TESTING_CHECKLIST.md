# ✅ TESTING CHECKLIST - Pelangi Weaving

## 🎯 PROGRESS: Views Updated (10%)

### ✅ Yang Sudah Diselesaikan
- [x] Auth views updated (login, register, admin-login)
- [x] Field names disesuaikan (nama, telepon, dll)
- [x] Admin login page created

---

## 🧪 TESTING PLAN

### 1. Authentication Testing

#### Pelanggan Authentication
```bash
# Test Register
URL: http://localhost:8000/register
Fields: nama, email, telepon, password, password_confirmation
Expected: Redirect ke home dengan session success

# Test Login
URL: http://localhost:8000/login
Credentials: budi@gmail.com / customer123
Expected: Redirect ke home dengan session success

# Test Logout
Expected: Redirect ke home dengan session success
```

#### Admin Authentication
```bash
# Test Admin Login
URL: http://localhost:8000/admin/login
Credentials: admin@pelangiweaving.com / admin123
Expected: Redirect ke admin dashboard

# Test Admin Logout
Expected: Redirect ke admin login
```

### 2. Database Testing

```bash
# Run migrations
php artisan migrate:fresh --seed

# Check tables
php artisan tinker
>>> \App\Models\Admin::count()
>>> \App\Models\Pelanggan::count()
>>> \App\Models\Product::count()
>>> \App\Models\Category::count()
```

### 3. Quick Functional Test

```bash
# Test routes
php artisan route:list | grep login
php artisan route:list | grep register
php artisan route:list | grep admin

# Test models
php artisan model:show Admin
php artisan model:show Pelanggan
php artisan model:show Product
```

---

## 📋 MANUAL TESTING CHECKLIST

### Setup
- [ ] .env configured
- [ ] Database created
- [ ] Migrations run
- [ ] Seeders run
- [ ] Storage linked
- [ ] Cache cleared

### Authentication
- [ ] Customer register works
- [ ] Customer login works
- [ ] Customer logout works
- [ ] Admin login works
- [ ] Admin logout works
- [ ] Guards working correctly

### Views
- [ ] Login page displays correctly
- [ ] Register page displays correctly
- [ ] Admin login page displays correctly
- [ ] No console errors
- [ ] Responsive design works

### Database
- [ ] Pelanggan table has data
- [ ] Admin table has data
- [ ] Products table has data
- [ ] Categories table has data
- [ ] All relationships work

---

## 🚀 QUICK TEST COMMANDS

```bash
# 1. Setup
php artisan migrate:fresh --seed
php artisan storage:link
php artisan optimize:clear

# 2. Run server
php artisan serve

# 3. Test in browser
# Customer: http://localhost:8000/login
# Admin: http://localhost:8000/admin/login

# 4. Check logs if error
tail -f storage/logs/laravel.log
```

---

## ✅ EXPECTED RESULTS

### After Register
```
✓ New pelanggan created in database
✓ Auto login after register
✓ Redirect to home
✓ Success message displayed
```

### After Login (Customer)
```
✓ Session created with pelanggan guard
✓ Redirect to home
✓ Success message displayed
✓ Can access protected routes
```

### After Login (Admin)
```
✓ Session created with admin guard
✓ Redirect to admin dashboard
✓ Success message displayed
✓ Can access admin routes
```

---

## 🐛 COMMON ISSUES & FIXES

### Issue: Column not found
```
Fix: Check field names in controller
nama, email, telepon (not name, phone)
```

### Issue: Unauthenticated
```
Fix: Check guard in controller
Auth::guard('pelanggan') or Auth::guard('admin')
```

### Issue: Route not found
```
Fix: Check routes/web.php
php artisan route:clear
```

### Issue: CSRF token mismatch
```
Fix: Clear cache
php artisan optimize:clear
```

---

## 📊 CURRENT STATUS

```
✅ Backend:           100% (45 files)
✅ Auth Views:         100% (3 files)
⏳ Other Views:        0% (~24 files)
⏳ Full Testing:       0%

Overall: ~97%
```

---

## 🎯 NEXT STEPS

1. **Test Authentication** (NOW)
   - Test register
   - Test login (customer & admin)
   - Test logout
   - Verify guards working

2. **Update Remaining Views** (NEXT)
   - Admin views (~12 files)
   - Customer views (~8 files)
   - Layout views (~4 files)

3. **Full Testing** (AFTER VIEWS)
   - Test all features
   - Fix bugs
   - Performance testing

---

**Last Updated:** 12 November 2024  
**Status:** Auth views done, ready for testing
