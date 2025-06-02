<?php

namespace App\Livewire\Components;

use App\Models\Product;
use Livewire\Component;

class LatestProducts extends Component
{

    public $latestProducts;

    public function mount(){
        $this->latestProducts = Product::latest()->take(12)->get();
    }

    public function render()
    {
        return view('livewire.components.latest-products');
    }
}
