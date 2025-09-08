<?php

namespace App\Livewire\Pages;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class MyOrder extends Component
{
    use WithPagination;

    public function render()
    {
        $orders = collect();
        
        if (Auth::guard('customer')->check()) {
            $customerId = Auth::guard('customer')->id();
            $orders = Order::with(['orderedProducts.product', 'shipping'])
                ->where('customer_id', $customerId)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('livewire.pages.my-order', [
            'orders' => $orders
        ]);
    }
}
