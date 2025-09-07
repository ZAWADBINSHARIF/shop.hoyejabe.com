<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderedProduct extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'product_total_price',
        'selected_color_code',
        'color_extra_price',
        'selected_size',
        'size_extra_price',
        'base_price',
        'extra_shipping_cost'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted()
    {
        static::saving(function (OrderedProduct $orderedProduct) {
            $orderedProduct->product_total_price =
                ($orderedProduct->base_price +
                    $orderedProduct->color_extra_price +
                    $orderedProduct->size_extra_price +
                    $orderedProduct->extra_shipping_cost) *
                $orderedProduct->quantity;
        });

        static::saved(function (OrderedProduct $orderedProduct) {
            $orderedProduct->order->recalculateTotalPrice();
        });

        static::deleted(function (OrderedProduct $orderedProduct) {
            $orderedProduct->order->recalculateTotalPrice();
        });
    }
}
