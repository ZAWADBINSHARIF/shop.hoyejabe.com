<?php

namespace App\Livewire\Pages;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderedProduct;
use App\Models\Product;
use App\Models\ProductComment;
use App\Models\CustomerFavorite;
use App\Models\ShippingCost;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SingleProduct extends Component
{
    public $slug;
    public $product;
    public $shippingCost;
    public $orderTrackingID = null;
    public $comments;
    public $newComment = '';
    public $newRating = 5;
    public $isFavorited = false;
    public $favoriteCount = 0;

    public $order = [
        'customer_id' => null,
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

    private function productTotalPrice(): float
    {
        return (float) ($this->orderedProduct['base_price'] + $this->orderedProduct['color_extra_price'] + $this->orderedProduct['size_extra_price'] + $this->orderedProduct['extra_shipping_cost']) * $this->orderedProduct['quantity'];
    }

    public function placeOrder()
    {
        // Check if customer is logged in
        if (!Auth::guard('customer')->check()) {
            $this->dispatch('close-modal', name: 'placing-order');
            $this->dispatch('open-signin-modal');
            $this->addError('auth', 'Please sign in to place an order.');
            return;
        }

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

        $checkProduct = Product::publishedProducts()->where('slug', $this->slug)->firstOrFail();

        if (!$checkProduct->published || $checkProduct->out_of_stock) {
            $this->addError('placing_order_problem', 'The product is out of stock.');
            return;
        }

        $shippingAreaDetails = ShippingCost::findOrFail($this->order['selected_shipping_area']);

        if ($shippingAreaDetails) {
            $this->order['shipping_cost'] = (float) $shippingAreaDetails->cost;
        }

        $this->order['order_status'] = OrderStatus::Pending->value;
        
        // Add customer_id from authenticated customer
        $customer = Auth::guard('customer')->user();
        $this->order['customer_id'] = $customer->id;

        $newOrder = Order::create($this->order);

        $this->orderTrackingID = $newOrder->order_tracking_id;

        $this->orderedProduct['order_id'] = $newOrder->id;
        $this->orderedProduct['product_id'] = $this->product->id;
        $this->orderedProduct['base_price'] = (float) $this->product->base_price;
        $this->orderedProduct['extra_shipping_cost'] = (float) $this->product->extra_shipping_cost;
        $this->orderedProduct['product_total_price'] = $this->productTotalPrice();
        OrderedProduct::create($this->orderedProduct);

        $this->dispatch('order-placed');
        $this->dispatch('order-placed-succefull');
    }

    public function submitComment()
    {
        if (!Auth::guard('customer')->check()) {
            $this->dispatch('open-signin-modal');
            $this->addError('auth', 'Please sign in to post a comment.');
            return;
        }

        $this->validate([
            'newComment' => 'required|string|min:10|max:500',
            'newRating' => 'required|integer|min:1|max:5',
        ], [
            'newComment.required' => 'Please write a comment.',
            'newComment.min' => 'Comment must be at least 10 characters.',
            'newComment.max' => 'Comment must not exceed 500 characters.',
            'newRating.required' => 'Please provide a rating.',
        ]);

        $customer = Auth::guard('customer')->user();

        // The is_verified_purchase will be automatically set by the model's booted method
        ProductComment::create([
            'product_id' => $this->product->id,
            'customer_id' => $customer->id,
            'comment' => $this->newComment,
            'rating' => $this->newRating,
            'customer_name' => $customer->full_name,
            // is_verified_purchase is automatically set in the model
        ]);

        $this->newComment = '';
        $this->newRating = 5;

        $this->loadComments();

        session()->flash('comment_message', 'Your comment has been submitted and is awaiting approval.');
    }

    public function loadComments()
    {
        $this->comments = ProductComment::with('customer')
            ->where('product_id', $this->product->id)
            ->published()
            ->latest()
            ->get();
    }

    public function mount(string $product_slug)
    {
        $this->slug = $product_slug;
        $this->product = Product::publishedProducts()->with('colors', 'sizes.size')->where('slug', $this->slug)->firstOrFail();
        $this->shippingCost = ShippingCost::all();
        $this->loadComments();
        $this->checkFavoriteStatus();
        $this->loadFavoriteCount();
    }
    
    public function checkFavoriteStatus()
    {
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();
            $this->isFavorited = CustomerFavorite::isFavorited($customer->id, $this->product->id);
        }
    }
    
    public function loadFavoriteCount()
    {
        $this->favoriteCount = $this->product->favorites()->count();
    }
    
    public function toggleFavorite()
    {
        if (!Auth::guard('customer')->check()) {
            $this->dispatch('open-signin-modal');
            session()->flash('error', 'Please login to add products to favorites');
            return;
        }
        
        $customer = Auth::guard('customer')->user();
        $isNowFavorited = CustomerFavorite::toggle($customer->id, $this->product->id);
        
        $this->isFavorited = $isNowFavorited;
        
        // Update favorite count
        $this->loadFavoriteCount();
        
        if ($isNowFavorited) {
            $this->dispatch('product-favorited', productId: $this->product->id);
            session()->flash('success', 'Product added to favorites!');
        } else {
            $this->dispatch('product-unfavorited', productId: $this->product->id);
            session()->flash('success', 'Product removed from favorites');
        }
    }

    public function showOrderModal()
    {
        // Check if customer is logged in
        if (!Auth::guard('customer')->check()) {
            $this->dispatch('open-signin-modal');
            session()->flash('error', 'Please sign in to place an order');
            return;
        }

        // Pre-fill customer information from logged in customer
        $customer = Auth::guard('customer')->user();
        $this->order['customer_name'] = $customer->full_name ?? '';
        $this->order['customer_mobile'] = $customer->phone_number ?? '';
        $this->order['address'] = $customer->address ?? '';
        $this->order['city'] = $customer->city ?? '';
        $this->order['upazila'] = $customer->upazila ?? '';
        $this->order['thana'] = $customer->thana ?? '';
        $this->order['post_code'] = $customer->post_code ?? '';

        // Dispatch event to show modal
        $this->dispatch('show-order-modal');
    }

    public function render()
    {
        return view('livewire.pages.single-product');
    }
}
