<?php

namespace App\Livewire\Components;

use App\Models\ProductCategory;
use Livewire\Component;

class CategorySlider extends Component
{

    public $categories;

    public function mount()
    {
        $this->categories  = ProductCategory::all();
    }

    public function render()
    {
        return view('livewire.components.category-slider');
    }
}
