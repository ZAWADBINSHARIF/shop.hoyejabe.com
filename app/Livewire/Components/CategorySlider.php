<?php

namespace App\Livewire\Components;

use Livewire\Component;

class CategorySlider extends Component
{

    public $categories;

    public function selectCategory($slug = null)
    {
        $this->dispatch('change-category', $slug);
    }

    public function render()
    {
        return view('livewire.components.category-slider');
    }
}
