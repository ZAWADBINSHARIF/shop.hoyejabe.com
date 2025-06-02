<?php

namespace App\Livewire\Pages;

use App\Models\Product;
use App\Models\ProductCategory;
use Livewire\Component;
use Livewire\WithPagination;

class Shop extends Component
{

    use WithPagination;

    public $categories;

    public function mount()
    {
        $this->categories  = ProductCategory::all();
    }
    
    public function render()
    {

        return view('livewire.pages.shop', [
            "products" => Product::latest()->paginate(1),
            // 'products' => 
        ]);
    }
}
