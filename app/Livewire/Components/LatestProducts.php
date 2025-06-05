<?php

namespace App\Livewire\Components;

use App\Models\Product;
use Livewire\Component;

class LatestProducts extends Component
{

    public $latestProducts, $searchInput, $totalShownProducts = 12;

    public function applySearch()
    {
        if ($this->searchInput)
            $this->redirect("/shop?search=" . $this->searchInput);
    }

    public function mount()
    {
    $this->latestProducts = Product::latest()->take($this->totalShownProducts)->get();
    }

    public function render()
    {
        return view('livewire.components.latest-products');
    }
}
