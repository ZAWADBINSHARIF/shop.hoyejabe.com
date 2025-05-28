<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCost extends Model
{
    protected $fillable = ['title', 'cost'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'selected_shipping_cost');
    }
}
