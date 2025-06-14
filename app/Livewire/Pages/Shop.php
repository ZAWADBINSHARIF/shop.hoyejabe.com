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

    public $categories, $searchInput, $perPageItem = 24;

    #[Url]
    public $sortBy, $category, $search;

    protected $listeners = [
        'change-category' => 'setCategory',
    ];


    public function mount()
    {
        $this->categories = ProductCategory::all();

        if ($this->search)
            $this->searchInput = $this->search;
    }

    public function updatedCategory()
    {
        $this->search = null;
        $this->searchInput = null;
        $this->setPage(1);
    }

    public function setCategory($slug)
    {
        $this->category = $slug;
        $this->resetPage();
    }

    public function applySearch()
    {
        if (!$this->searchInput) {
            $this->searchInput = null;
        }

        $this->search = $this->searchInput;
        $this->category = null;
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::publishedProducts()->where('published', true);

        if ($this->category) {
            $query->whereHas('category', function ($query) {
                $query->where('slug', $this->category);
            });
        }

        if ($this->search) {
            $query->whereAny([
                'name',
                'highlighted_description',
                'details_description'
            ], 'like', '%' . $this->search . '%');
        }

        if ($this->sortBy === 'price_low_high') {
            $query->orderBy('base_price', 'asc');
        } elseif ($this->sortBy === 'price_high_low') {
            $query->orderBy('base_price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate($this->perPageItem);

        return view('livewire.pages.shop', [
            'products' => $products,
        ]);
    }
}
