<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

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


    protected static function booted()
    {
        static::updating(function (Product $model) {
            $originalPorductImages = $model->getOriginal('images') ?? [];
            $newServiceImages = $model->images ?? [];

            // Ensure both are arrays
            if (!is_array($originalPorductImages)) {
                $originalPorductImages = json_decode($originalPorductImages, true) ?? [];
            }
            if (!is_array($newServiceImages)) {
                $newServiceImages = json_decode($newServiceImages, true) ?? [];
            }

            $deleteImages = array_diff($originalPorductImages, $newServiceImages);

            foreach ($deleteImages as $image) {
                Storage::disk('public')->delete($image);
            }
        });

        static::deleting(function (Product $model) {
            $deleteImages = $model->getOriginal('images');

            foreach ($deleteImages as $image) {
                Storage::disk('public')->delete($image);
            }
        });
    }

    public static function publishedProducts()
    {
        return self::where('published', true);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category');
    }

    public function colors(): HasMany
    {
        return $this->hasMany(ProductColor::class);
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class);
    }

    public function orderedProducts(): HasMany
    {
        return $this->hasMany(OrderedProduct::class);
    }
}
