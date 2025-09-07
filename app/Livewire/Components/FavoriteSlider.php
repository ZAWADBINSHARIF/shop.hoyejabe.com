<?php

namespace App\Livewire\Components;

use App\Models\CustomerFavorite;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class FavoriteSlider extends Component
{
    public $favorites = [];
    public $isOpen = false;
    
    public function mount()
    {
        $this->loadFavorites();
    }
    
    #[On('product-favorited')]
    #[On('product-unfavorited')]
    public function loadFavorites()
    {
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();
            $this->favorites = $customer->favoriteProducts()
                ->get();
        } else {
            $this->favorites = collect([]);
        }
    }
    
    public function removeFavorite($productId)
    {
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();
            CustomerFavorite::toggle($customer->id, $productId);
            $this->loadFavorites();
            $this->dispatch('product-unfavorited', productId: $productId);
        }
    }
    
    #[On('open-favorite-slider')]
    public function openSlider()
    {
        $this->loadFavorites();
        $this->isOpen = true;
    }
    
    #[On('close-favorite-slider')]
    public function closeSlider()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.components.favorite-slider');
    }
}
