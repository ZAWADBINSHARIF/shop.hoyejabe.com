<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'images',
        'highlighted_description',
        'details_description',
        'product_category',
        'base_price',
        'extra_shipping_cost',
        'published',
        'out_of_stock'
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category');
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function orderedProducts()
    {
        return $this->hasMany(OrderedProduct::class);
    }
}
