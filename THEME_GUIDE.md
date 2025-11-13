# 🎨 Pelangi Weaving - Theme Guide

## Theme Overview

**Style**: Clean, Flat Design (No Gradients)
**Inspiration**: Blibli.com
**Primary Color**: Blue (#0095DA)
**Design Philosophy**: Modern, Professional, E-commerce Ready

---

## 🎨 Color Palette

### Primary Colors
```css
--primary-blue: #0095DA        /* Main brand color */
--primary-blue-dark: #0076B6   /* Hover states */
--primary-blue-light: #E6F5FB  /* Backgrounds */
```

### Secondary Colors
```css
--secondary-orange: #FF6600     /* Accent color */
--secondary-orange-dark: #E65C00 /* Hover states */
```

### Neutral Colors
```css
--gray-50: #F8F9FA   /* Lightest */
--gray-100: #F1F3F5
--gray-200: #E9ECEF
--gray-300: #DEE2E6
--gray-400: #CED4DA
--gray-500: #ADB5BD
--gray-600: #6C757D
--gray-700: #495057
--gray-800: #343A40
--gray-900: #212529  /* Darkest */
```

### Status Colors
```css
--success: #28A745   /* Green */
--warning: #FFC107   /* Yellow */
--danger: #DC3545    /* Red */
--info: #17A2B8      /* Cyan */
```

---

## 🎯 Design Principles

### 1. Flat Design
- ❌ No gradients
- ❌ No heavy shadows
- ✅ Solid colors
- ✅ Subtle shadows for depth
- ✅ Clean borders

### 2. Consistent Spacing
- Border radius: 6-8px
- Padding: 0.5rem - 1.5rem
- Margins: 0.25rem - 2rem

### 3. Typography
- Font weight: 400 (normal), 500 (medium), 600 (semibold), 700 (bold)
- Font sizes: 0.875rem - 1.5rem
- Line height: 1.5

### 4. Shadows
```css
--shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05)
--shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1)
--shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1)
--shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1)
```

---

## 🧩 Component Styling

### Buttons
```html
<!-- Primary Button -->
<button class="btn btn-primary">Primary Action</button>

<!-- Outline Button -->
<button class="btn btn-outline-primary">Secondary Action</button>

<!-- Sizes -->
<button class="btn btn-primary btn-sm">Small</button>
<button class="btn btn-primary">Normal</button>
<button class="btn btn-primary btn-lg">Large</button>
```

**Features**:
- Rounded corners (6px)
- Hover lift effect
- Smooth transitions
- No gradients

### Cards
```html
<div class="card">
    <div class="card-header">Card Title</div>
    <div class="card-body">
        Card content here
    </div>
</div>
```

**Features**:
- Subtle border
- Hover shadow effect
- Lift on hover
- Clean header

### Badges
```html
<span class="badge bg-primary">Primary</span>
<span class="badge bg-success">Success</span>
<span class="badge bg-warning text-dark">Warning</span>
<span class="badge bg-danger">Danger</span>
```

**Features**:
- Rounded corners
- Solid colors
- Good contrast

### Forms
```html
<div class="mb-3">
    <label class="form-label">Label</label>
    <input type="text" class="form-control" placeholder="Input">
</div>
```

**Features**:
- Blue focus ring
- Smooth transitions
- Clear validation states

---

## 🎨 Usage Examples

### Product Card
```html
<div class="card product-card">
    <img src="..." class="card-img-top" alt="Product">
    <div class="card-body">
        <span class="badge bg-primary mb-2">Category</span>
        <h5 class="card-title">Product Name</h5>
        <p class="text-primary fw-bold">Rp 1.000.000</p>
        <a href="#" class="btn btn-primary w-100">View Detail</a>
    </div>
</div>
```

### Category Card
```html
<div class="card category-card">
    <i class="bi bi-tag-fill text-primary display-4"></i>
    <h5 class="mt-3">Category Name</h5>
    <p class="text-muted">10 Products</p>
</div>
```

### Statistics Card
```html
<div class="card bg-primary text-white stat-card">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h6>Total Orders</h6>
            <h2>1,234</h2>
        </div>
        <i class="bi bi-cart-check display-4"></i>
    </div>
</div>
```

---

## 🎯 Custom Utility Classes

### Background Colors
```html
<div class="bg-light-blue">Light blue background</div>
```

### Text Colors
```html
<span class="text-blue">Blue text</span>
```

### Border Colors
```html
<div class="border border-blue">Blue border</div>
```

### Hover Effects
```html
<div class="hover-shadow">Shadow on hover</div>
<div class="hover-lift">Lift on hover</div>
```

---

## 📱 Responsive Design

### Breakpoints (Bootstrap 5)
```css
/* Small devices (landscape phones, 576px and up) */
@media (min-width: 576px) { ... }

/* Medium devices (tablets, 768px and up) */
@media (min-width: 768px) { ... }

/* Large devices (desktops, 992px and up) */
@media (min-width: 992px) { ... }

/* Extra large devices (large desktops, 1200px and up) */
@media (min-width: 1200px) { ... }
```

---

## 🎨 Theme Customization

### Changing Primary Color

Edit `public/css/custom.css`:

```css
:root {
    --primary-blue: #YOUR_COLOR;
    --primary-blue-dark: #YOUR_DARKER_COLOR;
    --primary-blue-light: #YOUR_LIGHTER_COLOR;
}
```

### Adding New Colors

```css
:root {
    --custom-color: #123456;
}

.bg-custom {
    background-color: var(--custom-color);
}

.text-custom {
    color: var(--custom-color);
}
```

---

## 🎯 Best Practices

### DO ✅
- Use consistent spacing
- Use solid colors (no gradients)
- Use subtle shadows
- Use smooth transitions
- Use proper contrast ratios
- Use semantic colors (success, warning, danger)

### DON'T ❌
- Don't use gradients
- Don't use heavy shadows
- Don't mix too many colors
- Don't use inconsistent spacing
- Don't use poor contrast

---

## 🎨 Color Usage Guide

### When to Use Each Color

**Primary Blue** (#0095DA):
- Main CTAs (Call to Action)
- Links
- Active states
- Brand elements

**Secondary Orange** (#FF6600):
- Special offers
- Promotions
- Accent elements
- Urgent actions

**Success Green** (#28A745):
- Success messages
- Completed states
- Positive actions
- Available status

**Warning Yellow** (#FFC107):
- Warning messages
- Pending states
- Caution alerts

**Danger Red** (#DC3545):
- Error messages
- Delete actions
- Critical alerts
- Out of stock

**Gray Shades**:
- Text (gray-700, gray-800, gray-900)
- Borders (gray-200, gray-300)
- Backgrounds (gray-50, gray-100)
- Disabled states (gray-400, gray-500)

---

## 📦 File Structure

```
public/
└── css/
    └── custom.css  (Main theme file)

resources/
└── views/
    └── layouts/
        ├── customer.blade.php  (Includes custom.css)
        └── admin.blade.php     (Includes custom.css)
```

---

## 🚀 Quick Start

### 1. Theme is Already Applied
All views automatically use the custom theme via layouts.

### 2. Using Theme Classes
```html
<!-- Use Bootstrap classes as normal -->
<button class="btn btn-primary">Button</button>

<!-- Theme automatically applies blue color -->
```

### 3. Custom Components
```html
<!-- Product card with hover effect -->
<div class="card product-card hover-lift">
    ...
</div>
```

---

## 🎨 Theme Comparison

### Before (Default Bootstrap)
- Primary color: Blue (#0d6efd)
- Gradients: Yes
- Shadows: Heavy
- Style: Generic

### After (Custom Theme)
- Primary color: Blibli Blue (#0095DA)
- Gradients: No (Flat design)
- Shadows: Subtle
- Style: E-commerce optimized

---

## 📝 Notes

- Theme uses CSS variables for easy customization
- All colors are accessible (WCAG AA compliant)
- Responsive design included
- No JavaScript required
- Compatible with Bootstrap 5
- Works with all modern browsers

---

## 🎊 Result

**Clean, professional e-commerce theme** inspired by Blibli with:
- ✅ Flat design (no gradients)
- ✅ Blue color scheme
- ✅ Modern & clean
- ✅ E-commerce optimized
- ✅ Fully responsive
- ✅ Easy to customize

**Enjoy your new theme!** 🎨
