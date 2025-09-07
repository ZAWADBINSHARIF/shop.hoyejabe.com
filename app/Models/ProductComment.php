<?php

namespace App\Models;

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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
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
