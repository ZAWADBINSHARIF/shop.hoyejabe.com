<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductComment extends Model
{
    protected $fillable = [
        'product_id',
        'customer_id',
        'comment',
        'rating',
        'is_verified_purchase',
        'is_approved',
        'is_visible',
        'customer_name',
        'approved_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean',
        'is_visible' => 'boolean',
        'approved_at' => 'datetime',
    ];

    protected $attributes = [
        'is_approved' => true,
        'is_visible' => true,
    ];

    protected static function booted()
    {
        static::creating(function (ProductComment $comment) {
            // Automatically verify if customer has purchased this product
            if ($comment->customer_id && $comment->product_id) {
                $comment->is_verified_purchase = $comment->hasCustomerPurchasedProduct();
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Check if the customer has purchased this product
     */
    public function hasCustomerPurchasedProduct(): bool
    {
        if (!$this->customer_id || !$this->product_id) {
            return false;
        }

        return OrderedProduct::whereHas('order', function ($query) {
            $query->where('customer_id', $this->customer_id)
                ->whereIn('order_status', [OrderStatus::Delivered->value]);
        })
            ->where('product_id', $this->product_id)
            ->exists();
    }

    /**
     * Update the verification status for this comment
     */
    public function updateVerificationStatus(): bool
    {
        $this->is_verified_purchase = $this->hasCustomerPurchasedProduct();
        return $this->save();
    }

    /**
     * Verify all comments for a specific customer
     */
    public static function verifyCustomerComments($customerId): int
    {
        $comments = static::where('customer_id', $customerId)->get();
        $updated = 0;

        foreach ($comments as $comment) {
            $wasVerified = $comment->is_verified_purchase;
            $isVerified = $comment->hasCustomerPurchasedProduct();
            
            if ($wasVerified !== $isVerified) {
                $comment->is_verified_purchase = $isVerified;
                $comment->save();
                $updated++;
            }
        }

        return $updated;
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopePublished($query)
    {
        return $query->approved()->visible();
    }

    public function scopeWithRating($query)
    {
        return $query->whereNotNull('rating');
    }

    public function getFormattedRatingAttribute()
    {
        return $this->rating ? number_format($this->rating, 1) : null;
    }

    public function getCustomerDisplayNameAttribute()
    {
        return $this->customer_name ?: $this->customer->full_name;
    }
}
