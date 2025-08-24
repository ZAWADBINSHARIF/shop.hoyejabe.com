<?php

namespace App\Livewire\Pages;

use App\Models\Order;
use Livewire\Attributes\Url;
use Livewire\Component;

class TrackOrder extends Component
{
    #[Url]
    public $trackingId;
    public $order;
    public $notFound = false;

    public function track()
    {
        $this->reset(['order', 'notFound']);

        if (!$this->trackingId) {
            $this->addError('trackingId', 'Order ID or Tracking ID is required.');
            return;
        }

        $this->order = Order::where('order_tracking_id', $this->trackingId)->with(['orderedProducts', 'shipping'])->first();

        if (!$this->order) {
            $this->notFound = true;
        }
    }

    public function mount(){

        if($this->trackingId){
            $this->track();
        }
    }

    public function render()
    {
        return view('livewire.pages.track-order');
    }
}
