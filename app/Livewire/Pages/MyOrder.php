<?php

namespace App\Livewire\Pages;

use App\Models\Order;
use Livewire\Component;

class MyOrder extends Component
{
    public $order;

    public function render()
    {

        $this->order = Order::with(['orderedProducts', 'shipping'])->first();

        return view('livewire.pages.my-order');
    }
}
