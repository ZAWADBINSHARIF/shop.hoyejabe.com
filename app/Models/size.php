<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class size extends Model
{
    protected $fillable = ['value'];

    public function productSizes()
    {
        return $this->hasMany(ProductSize::class);
    }
}
