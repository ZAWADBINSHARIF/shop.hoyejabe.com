<?php

namespace App\Livewire\Components;

use App\Models\CarouselImage;
use Livewire\Component;

class Carousel extends Component
{

    // public $carousel = [
    //     [
    //         'title' => 'Stripy Zig Zag Jigsaw Pillow and Duvet Set',
    //         'image' =>  'https://images.unsplash.com/photo-1422190441165-ec2956dc9ecc?auto=format&fit=crop&w=1600&q=80',
    //         'product_url' => 'https://www.lionbd.com'
    //     ],
    //     [
    //         'title' => 'Real Bamboo Wall Clock',
    //         'image' => 'https://images.unsplash.com/photo-1533090161767-e6ffed986c88?auto=format&fit=crop&w=1600&q=80',
    //         'product_url' => 'https://www.lionbd.com'
    //     ],
    //     [
    //         'title' => 'Brown and Blue Hardbound Book',
    //         'image' => 'https://images.unsplash.com/photo-1519327232521-1ea2c736d34d?auto=format&fit=crop&w=1600&q=80',
    //         'product_url' => 'https://www.lionbd.com'
    //     ],
    // ];

    public $carousel;

    public function mount()
    {
        $this->carousel = CarouselImage::all();
    }

    public function render()
    {
        return view('livewire.components.carousel');
    }
}
