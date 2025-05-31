<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_mobile',
        'city',
        'upazila',
        'thana',
        'post_code',
        'selected_shipping_cost',
        'shipping_cost',
        'extra_shipping_cost',
        'total_price',
        'order_status'
    ];

    protected $casts = [
        'order_status' => OrderStatus::class,
    ];

    public function shipping()
    {
        return $this->belongsTo(ShippingCost::class, 'selected_shipping_cost');
    }

    public function orderedProducts()
    {
        return $this->hasMany(OrderedProduct::class);
    }
}
