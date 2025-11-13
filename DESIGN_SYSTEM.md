# Pelangi Weaving - Design System

## Overview
Design system modern dan profesional menggunakan Tailwind CSS dengan fokus pada user experience yang optimal.

## Color Palette

### Primary Colors
- **Blue 600**: `#0095DA` - Primary brand color
- **Blue 700**: `#0076B6` - Hover states
- **Blue 50**: `#E6F5FB` - Light backgrounds

### Accent Colors
- **Orange**: `#FF6600` - Call-to-action
- **Green**: `#10B981` - Success states
- **Red**: `#EF4444` - Error states

### Neutral Colors
- **Gray 50-900**: Full grayscale palette
- **White**: `#FFFFFF`
- **Black**: `#111827`

## Typography

### Font Family
- Primary: System font stack (optimized for each OS)
- Fallback: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto

### Font Sizes
- **Heading 1**: 3xl-6xl (responsive)
- **Heading 2**: 2xl-4xl
- **Body**: base (16px)
- **Small**: sm (14px)

## Components

### Buttons
- **Primary**: Blue background, white text
- **Secondary**: White background, blue border
- **Sizes**: sm, base, lg
- **States**: hover, focus, active, disabled

### Cards
- **Shadow**: md, lg, xl, 2xl
- **Radius**: lg (12px), xl (16px), 2xl (24px)
- **Hover**: Lift effect with shadow increase

### Forms
- **Input Height**: 48px (py-3)
- **Border**: 1px solid gray-300
- **Focus**: 2px ring blue-500
- **Icons**: Left-aligned, gray-400

### Navigation
- **Navbar Height**: 64px (h-16)
- **Sticky**: Top position
- **Shadow**: sm
- **Mobile**: Hamburger menu with slide-in

## Layout

### Container
- **Max Width**: 7xl (1280px)
- **Padding**: px-4 sm:px-6 lg:px-8

### Grid
- **Columns**: 1 (mobile), 2 (tablet), 3-4 (desktop)
- **Gap**: 4-6 (16-24px)

### Spacing
- **Section Padding**: py-16 (64px)
- **Element Margin**: mb-4 to mb-12

## Animations

### Transitions
- **Duration**: 200-300ms
- **Easing**: ease, ease-out, cubic-bezier

### Effects
- **Fade In**: Opacity + translateY
- **Slide In**: Opacity + translateX
- **Hover Lift**: translateY(-4px)
- **Scale**: scale(1.05-1.1)

## Responsive Breakpoints

- **sm**: 640px
- **md**: 768px
- **lg**: 1024px
- **xl**: 1280px
- **2xl**: 1536px

## Best Practices

1. **Mobile First**: Design for mobile, enhance for desktop
2. **Accessibility**: Proper contrast ratios, focus states
3. **Performance**: Optimize images, lazy loading
4. **Consistency**: Use design tokens consistently
5. **Feedback**: Clear loading, success, error states

## Icons
- **Library**: Bootstrap Icons
- **Size**: text-xl to text-6xl
- **Color**: Inherit from parent or specific utility classes

## Images
- **Aspect Ratio**: aspect-square, aspect-video
- **Object Fit**: object-cover
- **Hover**: Scale effect (1.1)
- **Loading**: Gradient placeholder

## Shadows
- **sm**: Subtle elevation
- **md**: Default cards
- **lg**: Hover states
- **xl**: Modals, dropdowns
- **2xl**: Hero sections

## Border Radius
- **sm**: 6px
- **base**: 8px
- **lg**: 12px
- **xl**: 16px
- **2xl**: 24px
- **full**: 9999px (circles)
