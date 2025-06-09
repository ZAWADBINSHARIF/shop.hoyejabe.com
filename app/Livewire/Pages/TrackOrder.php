<?php

namespace App\Livewire\Pages;

use App\Models\Order;
use Livewire\Component;

class TrackOrder extends Component
{
    public $trackingId;
    public $order;
    public $notFound = false;

    public function track()
    {
        $this->reset(['order', 'notFound']);

        if (!$this->trackingId) {
            $this->addError('trackingId', 'Tracking ID is required.');
            return;
        }

        $this->order = Order::where('order_tracking_id', $this->trackingId)->first();

        if (!$this->order) {
            $this->notFound = true;
        }
    }

    public function render()
    {
        return view('livewire.pages.track-order');
    }
}
