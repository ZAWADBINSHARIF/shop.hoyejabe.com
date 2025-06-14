<?php

namespace App\Livewire\Components;

use App\Models\CarouselImage;
use Livewire\Component;

class Carousel extends Component
{

    public $carousel;

    public function mount()
    {
        $this->carousel = CarouselImage::orderBy('sort', 'asc')->get();
    }

    public function render()
    {
        return view('livewire.components.carousel');
    }
}
