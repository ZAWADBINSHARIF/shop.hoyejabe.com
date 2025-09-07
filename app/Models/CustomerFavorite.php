<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerFavorite extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
    ];

    /**
     * Get the customer that owns the favorite.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the product that is favorited.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * Check if a product is favorited by a customer
     */
    public static function isFavorited($customerId, $productId): bool
    {
        return self::where('customer_id', $customerId)
                   ->where('product_id', $productId)
                   ->exists();
    }
    
    /**
     * Toggle favorite status
     */
    public static function toggle($customerId, $productId): bool
    {
        $favorite = self::where('customer_id', $customerId)
                        ->where('product_id', $productId)
                        ->first();
        
        if ($favorite) {
            $favorite->delete();
            return false; // Removed from favorites
        } else {
            self::create([
                'customer_id' => $customerId,
                'product_id' => $productId,
            ]);
            return true; // Added to favorites
        }
    }
}
