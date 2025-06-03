<?php

namespace App\Livewire\Pages;

use App\Models\Product;
use App\Models\ProductCategory;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Shop extends Component
{

    use WithPagination;

    public $categories;

    #[Url]
    public $sortBy;

    #[Url()]
    public $category;

    protected $listeners = [
        'change-category' => 'setCategory',
    ];


    public function mount()
    {
        $this->categories = ProductCategory::all();
    }

    public function updatedCategory()
    {
        $this->setPage(1);
    }

    public function setCategory($slug)
    {
        $this->category = $slug;
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query();

        if ($this->category) {
            $query->whereHas('category', function ($query) {
                $query->where('slug', $this->category);
            });
        }

        if ($this->sortBy === 'price_low_high') {
            $query->orderBy('base_price', 'asc');
        } elseif ($this->sortBy === 'price_high_low') {
            $query->orderBy('base_price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(2);

        return view('livewire.pages.shop', [
            'products' => $products,
        ]);
    }
}
