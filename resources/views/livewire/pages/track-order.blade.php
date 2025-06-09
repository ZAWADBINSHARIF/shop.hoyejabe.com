<div class="max-w-xl mx-auto mt-10 space-y-6">
    <h2 class="text-xl text-center text-gray-800">Track Your Order</h2>

    {{-- Flux UI Input --}}
    <flux:field
        label="Tracking ID"
        hint="Enter the unique tracking ID you received"
        :required="true"
    >
        <flux:input
            wire:model.defer="trackingId"
            placeholder="e.g. ORD1234567 or The mobile number used to order"
            class="flux-input w-full"
        />
    </flux:field>

    @error('trackingId')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror

    {{-- Submit Button --}}
    <div class="flex justify-end">
        <flux:button variant="primary" wire:click="track" class="flux-btn flux-btn-primary">Track Order</flux:button>
    </div>

    {{-- If not found --}}
    @if ($notFound)
        <div class="bg-red-100 text-red-700 p-4 rounded border border-red-200">
            ❌ Order not found. Please check your tracking ID.
        </div>
    @endif

    {{-- Order info display --}}
    @if ($order)
        <div class="mt-6 bg-gray-50 border border-gray-200 p-6 rounded shadow-sm space-y-3">
            <h3 class="text-lg font-semibold text-gray-800">Order Information</h3>

            <div class="text-sm space-y-1 text-gray-700">
                <div><strong>Order Tracking Id:</strong> {{ $order->order_tracking_id }}</div>
                <div><strong>Customer:</strong> {{ $order->customer_name }}</div>
                <div><strong>Mobile:</strong> {{ $order->customer_mobile }}</div>
                <div><strong>Address:</strong> {{ $order->address }}, {{ $order->city }}</div>
                <div><strong>Status:</strong> {{ $order->status ?? 'Processing' }}</div>
                <div><strong>Total:</strong> ৳{{ number_format($order->total_price, 2) }}</div>
            </div>
        </div>
    @endif
</div>
