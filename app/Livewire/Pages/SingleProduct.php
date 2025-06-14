<?php

namespace App\Livewire\Pages;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderedProduct;
use App\Models\Product;
use App\Models\ShippingCost;
use Livewire\Component;

class SingleProduct extends Component
{
    public $slug;
    public $product;
    public $shippingCost;
    public $orderTrackingID = null;

    public $order = [
        'customer_name' => '',
        'customer_mobile' => '',
        'address' => '',
        'city' => '',
        'upazila' => '',
        'thana' => '',
        'post_code' => '',
        'selected_shipping_area' => null,
        'shipping_cost' => 0,
        'extra_shipping_cost' => 0,
        'total_price' => 0,
        'order_status' => null
    ];

    public $orderedProduct = [
        'order_id' => null,
        'product_id' => null,
        'product_name' => null,
        'quantity' => null,
        'selected_color_code' => null,
        'color_extra_price' => 0,
        'selected_size' => null,
        'size_extra_price' => 0,
        'base_price' => null,
        'product_total_price' => null,
        'extra_shipping_cost' => 0,
    ];

    public function placeOrder()
    {
        $this->validate([
            'order.customer_name' => 'required|string|max:255',
            'order.customer_mobile' => 'required|string|max:20',
            'order.address' => 'required|string|max:255',
            'order.city' => 'required|string|max:255',
            'order.upazila' => 'nullable|string|max:255',
            'order.thana' => 'nullable|string|max:255',
            'order.post_code' => 'nullable|string|max:255',
            'order.selected_shipping_area' => 'required|numeric',
            'order.extra_shipping_cost' => 'required|numeric',
            'order.total_price' => 'required|numeric',
        ], [
            'order.selected_shipping_area' => 'Select one of the shipping area'
        ], [
            'order.customer_name' => 'customer name',
            'order.customer_mobile' => 'mobile number',
            'order.address' => 'address',
            'order.city' => 'city',
            'order.upazila' => 'upazila',
            'order.thana' => 'thana',
            'order.post_code' => 'post code',
            'order.selected_shipping_area' => 'shipping area',
        ]);

        $shippingAreaDetails = ShippingCost::findOrFail($this->order['selected_shipping_area']);


        if ($shippingAreaDetails) {
            $this->order['shipping_cost'] = (float) $shippingAreaDetails->cost;
        }

        $this->order['order_status'] = OrderStatus::Pending->value;

        $newOrder = Order::create($this->order);

        $this->orderTrackingID = $newOrder->order_tracking_id;

        $this->orderedProduct['order_id'] = $newOrder->id;
        $this->orderedProduct['product_id'] = $this->product->id;
        $this->orderedProduct['base_price'] = (float) $this->product->base_price;
        $this->orderedProduct['extra_shipping_cost'] = (float) $this->product->extra_shipping_cost;
        $this->orderedProduct['product_total_price'] = ($this->orderedProduct['base_price'] + $this->orderedProduct['color_extra_price'] + $this->orderedProduct['size_extra_price']) * $this->orderedProduct['quantity'] + $this->orderedProduct['extra_shipping_cost'];

        OrderedProduct::create($this->orderedProduct);

        $this->dispatch('order-placed');
        $this->dispatch('order-placed-succefull');
    }

    public function mount(string $product_slug)
    {
        $this->slug = $product_slug;
        $this->product = Product::publishedProducts()->with('colors', 'sizes.size')->where('slug', $this->slug)->firstOrFail();
        $this->shippingCost = ShippingCost::all();
    }

    public function render()
    {
        return view('livewire.pages.single-product');
    }
}
