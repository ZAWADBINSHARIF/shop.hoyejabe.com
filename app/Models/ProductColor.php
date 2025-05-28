<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    protected $fillable = ['product_id', 'color_code', 'extra_price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
