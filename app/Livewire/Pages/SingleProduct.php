<?php

namespace App\Livewire\Pages;

use App\Models\Product;
use App\Models\ShippingCost;
use Livewire\Component;

class SingleProduct extends Component
{
    public $slug;
    public $product;
    public $shippingCost;

    public function mount(string $product_slug)
    {
        $this->slug = $product_slug;
        $this->product = Product::with('colors', 'sizes.size')->where('slug', $this->slug)->firstOrFail();
        $this->shippingCost = ShippingCost::all();
    }

    public function render()
    {
        return view('livewire.pages.single-product');
    }
}
