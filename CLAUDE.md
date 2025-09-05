# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 e-commerce application with Livewire 3, Flux UI components, and Filament admin panel. The project uses Tailwind CSS v4 for styling and Vite for asset bundling.

## Key Technologies

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Livewire 3, Flux UI, Blade templates
- **Admin Panel**: Filament 3.3
- **Database**: MariaDB/MySQL
- **Styling**: Tailwind CSS v4
- **Build Tools**: Vite, NPM/PNPM

## Development Commands

### Starting Development Server
```bash
# Start Laravel server on port 4000
npm run start

# Start Laravel server with network access
npm run open

# Run full development environment (server, queue, logs, vite)
composer run dev

# Run Vite dev server only
npm run dev
```

### Build and Testing
```bash
# Build frontend assets
npm run build

# Run tests
composer test
# Or
php artisan test

# Run specific test
php artisan test --filter TestName

# Code formatting (Laravel Pint)
./vendor/bin/pint

# Run database migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback
```

### Artisan Commands
```bash
# Create Livewire component
php artisan make:livewire ComponentName

# Create Filament resource
php artisan make:filament-resource ResourceName

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Queue management
php artisan queue:listen --tries=1
```

## Project Architecture

### Directory Structure

```
app/
├── Filament/         # Admin panel resources and pages
│   ├── Resources/    # CRUD resources (Products, Orders, Users, etc.)
│   └── Pages/        # Custom admin pages
├── Livewire/         # Frontend Livewire components
│   ├── Components/   # Reusable components (Navbar, Footer, Sliders)
│   ├── Pages/        # Full page components (Home, Shop, SingleProduct)
│   └── Modals/       # Modal components (SignIn, SignUp, ForgotPassword)
├── Models/           # Eloquent models
├── Forms/            # Custom form components
└── Enums/            # Application enums

resources/
├── views/
│   ├── components/   # Blade components
│   ├── livewire/     # Livewire component views
│   └── flux/         # Flux UI customizations
├── css/              # Stylesheets
└── js/               # JavaScript files
```

### Core Models and Relationships

- **Product**: Main product entity with images, categories, sizes, colors
- **Order**: Customer orders with OrderedProduct pivot
- **User**: Authentication and user management
- **ProductCategory**: Product categorization
- **ShippingCost**: Shipping configuration
- **CarouselImage**: Homepage carousel management

### Routing

All routes are defined in `routes/web.php` using Livewire full-page components:
- `/` - Home page
- `/shop` - Product listing
- `/shop/{product_slug}` - Single product view
- `/track-order` - Order tracking
- `/my-order` - User order history
- `/about` - About page
- `/contact-us` - Contact page

### Admin Panel

Filament admin panel accessible at `/admin` with resources for:
- Products (with image cropping to 1:1 ratio)
- Orders
- Users
- Product Categories
- Shipping Costs
- Carousel Images
- Sizes

## Current Work in Progress

Based on git status, the following files have uncommitted changes:
- Product resource admin panel modifications
- Single product card component
- Navbar component
- Profile slider component
- Track order page
- My Order page (new feature being added)

## Database Configuration

- Connection: MariaDB/MySQL
- Default database name: `shop_hoyejabe`
- Session driver: database
- Cache driver: database
- Queue driver: database

## Important Notes

1. Images are automatically cropped to 1:1 aspect ratio when uploading product images
2. The application uses database for sessions, cache, and queue management
3. Development server runs on port 4000 by default
4. Tailwind CSS v4 is configured via Vite plugin
5. Authentication uses Livewire modals for SignIn/SignUp flows