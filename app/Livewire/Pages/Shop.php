<?php

namespace App\Livewire\Pages;

use App\Models\Product;
use App\Models\ProductCategory;
use Livewire\Component;

class Shop extends Component
{

    public $categories;
    public $products;

    public function mount()
    {
        $this->categories  = ProductCategory::all();
        $this->products  = Product::all();
    }

    public function render()
    {
        return view('livewire.pages.shop');
    }
}
