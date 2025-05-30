<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CarouselImage extends Model
{
    protected $fillable = ['title', 'image', 'product_url'];

    protected static function booted()
    {

        static::updating(function (CarouselImage $model) {
            if ($model->isDirty('image') && $model->getOriginal("image")) {
                Storage::disk('public')->delete($model->getOriginal("image"));
            }
        });

        static::deleting(function (CarouselImage $model) {
            if ($model->getOriginal("image")) {
                Storage::disk('public')->delete($model->getOriginal("image"));
            }
        });
    }
}
