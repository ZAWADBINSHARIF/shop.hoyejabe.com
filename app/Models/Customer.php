<?php

namespace App\Models;

use App\Traits\HasSmsNotifications;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, HasSmsNotifications;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'phone_number',
        'email',
        'city',
        'upazila',
        'thana',
        'post_code',
        'address',
        'delivery_address', // Keep for backward compatibility
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the orders for the customer.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the comments for the customer.
     */
    public function comments()
    {
        return $this->hasMany(ProductComment::class);
    }

    /**
     * Get the customer's favorite products.
     */
    public function favorites()
    {
        return $this->hasMany(CustomerFavorite::class);
    }

    /**
     * Get the customer's favorite products (direct relation).
     */
    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'customer_favorites')
                    ->withTimestamps();
    }

    /**
     * Check if customer has favorited a product
     */
    public function hasFavorited($productId): bool
    {
        return $this->favorites()->where('product_id', $productId)->exists();
    }

    /**
     * Get the number of orders for the customer.
     *
     * @return int
     */
    public function getOrdersCountAttribute()
    {
        return $this->orders()->count();
    }
}