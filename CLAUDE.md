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
- **SMS Services**: SMSQ and BulkSMSBD integrations

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
- **Customer**: Customer profiles with phone numbers for SMS
- **User**: Admin authentication and management
- **ProductCategory**: Product categorization
- **ShippingCost**: Shipping configuration
- **CarouselImage**: Homepage carousel management
- **OrderedProduct**: Pivot table for order items with quantity and extras

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
- Orders (with status management and order tracking)
- Customers (with SMS messaging capabilities)
- Users (admin accounts)
- Product Categories
- Shipping Costs
- Carousel Images
- Sizes
- Send Customer Message (bulk SMS page)

## SMS Service Architecture

The application has dual SMS gateway support:
1. **SMSQ** (Primary): Uses comma-separated numbers for bulk sending
2. **BulkSMSBD** (Alternative): Bangladesh-focused SMS provider

Key SMS features:
- Optimized bulk sending (single API call for same message to multiple recipients)
- Message personalization with {name} and {first_name} placeholders
- Automatic phone number formatting for Bangladesh (+880)
- Smart message grouping for personalized bulk sends

## Database Configuration

- Connection: MariaDB/MySQL
- Default database name: `shop_hoyejabe`
- Session driver: database
- Cache driver: database
- Queue driver: database

## Environment Configuration

Required SMS configuration in `.env`:
```
# SMSQ Configuration
SMSQ_API_KEY=your_api_key
SMSQ_CLIENT_ID=your_client_id
SMSQ_SENDER_ID=your_sender_id

# BulkSMSBD Configuration
BULKSMSBD_API_KEY=your_api_key
BULKSMSBD_SENDER_ID=your_sender_id
```

## Important Implementation Details

1. **Image Processing**: Product images automatically cropped to 1:1 aspect ratio on upload
2. **Session Management**: Uses database driver for sessions, cache, and queue
3. **Port Configuration**: Development server runs on port 4000 by default
4. **Frontend Stack**: Tailwind CSS v4 via Vite plugin, Flux UI components with Livewire 3
5. **Authentication Flow**: Customer auth uses Livewire modals (SignIn/SignUp/ForgotPassword)
6. **SMS Optimization**: `sendBulkSameMessage()` method reduces API calls from N to 1 for identical messages
7. **Order Tracking**: Each order has unique `order_tracking_id` for customer tracking