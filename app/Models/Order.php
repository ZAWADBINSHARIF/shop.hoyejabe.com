<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'customer_name',
        'customer_mobile',
        'address',
        'city',
        'upazila',
        'thana',
        'post_code',
        'selected_shipping_area',
        'shipping_cost',
        'extra_shipping_cost',
        'total_price',
        'order_status',
        'order_tracking_id'
    ];

    protected $casts = [
        'order_status' => OrderStatus::class,
    ];

    public static function booted()
    {
        static::creating(function (Order $order) {
            do {
                $randomId = "ORD" . "-" . now(6)->format('dmY') . "-" . strtoupper(Str::random(8));
            } while (self::where('order_tracking_id', $randomId)->exists());

            $order->order_tracking_id = $randomId;
        });
    }

    public function shipping()
    {
        return $this->belongsTo(ShippingCost::class, 'selected_shipping_area');
    }

    public function orderedProducts()
    {
        return $this->hasMany(OrderedProduct::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function recalculateTotalPrice()
    {
        $productsTotal = $this->orderedProducts()->sum('product_total_price');

        $this->total_price = $productsTotal +
            $this->shipping_cost +
            $this->extra_shipping_cost;

        $this->saveQuietly();
    }
}
