<div class="max-w-3xl mx-auto mt-10 space-y-6">
    <h2 class="text-xl text-center text-gray-800">Track Your Order</h2>

    {{-- Flux UI Input --}}
    <flux:field label="Tracking ID" hint="Enter the unique tracking ID you received" :required="true">
        <flux:input wire:model.defer="trackingId" icon="magnifying-glass" placeholder="ORD-123456789"
            class="flux-input w-full" />
        <flux:text class="text-xs">Write your Order ID or Tracking ID to know the order status</flux:text>
        <flux:error name="trackingId" />
    </flux:field>

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
        <div class="mt-6 bg-gray-50 border border-gray-200 p-6 rounded shadow-md space-y-5">
            {{-- Order Info --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Order Information</h3>
                <div class="text-sm space-y-1 text-gray-700">
                    <div><strong>Order Tracking Id:</strong> {{ $order->order_tracking_id }}</div>
                    <div><strong>Customer:</strong> {{ $order->customer_name }}</div>
                    <div><strong>Mobile:</strong> {{ $order->customer_mobile }}</div>
                    <div><strong>Address:</strong> {{ $order->address }}, {{ $order->city }}</div>
                    <div><strong>Status:</strong>
                        <flux:badge variant="solid" color="indigo">
                            {{ ucwords((string) $order->order_status->value) ?? 'Processing' }}
                        </flux:badge>
                    </div>
                    <div><strong>Shipping Area:</strong> {{ $order->shipping->title }}</div>
                    <div><strong>Shipping Cost:</strong> ৳{{ number_format($order->shipping_cost, 2) }}</div>
                    <div><strong>Total:</strong> ৳{{ number_format($order->total_price, 2) }}</div>
                </div>
            </div>

            {{-- Ordered Products --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Ordered Products</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border-y border-gray-200">
                        <thead class="bg-gray-100 text-gray-700 border-y">
                            <tr>
                                <th class="px-4 py-2 border-y">#</th>
                                <th class="px-4 py-2 border-y">Product Name</th>
                                <th class="px-4 py-2 border-y">Size</th>
                                <th class="px-4 py-2 border-y">Color</th>
                                <th class="px-4 py-2 border-y">Quantity</th>
                                <th class="px-4 py-2 border-y">Base Price</th>
                                <th class="px-4 py-2 border-y">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderedProducts as $index => $product)
                                <tr class="text-gray-700 hover:bg-gray-50">
                                    <td class="px-4 py-2 border-y">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 border-y font-medium">{{ $product->product_name }}</td>
                                    <td class="px-4 py-2 border-y text-center">{{ $product->selected_size }}
                                        @if ($product?->size_extra_price > 0)
                                            <div>
                                                +৳{{ $product->size_extra_price }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border-y text-center">
                                        <div class="text-center flex justify-center">
                                            <div class="w-5 h-5 rounded-full"
                                                style="
                                        background-color: {{ $product->selected_color_code }}
                                        ">
                                            </div>
                                        </div>
                                        @if ($product?->color_extra_price > 0)
                                            <div>
                                                +৳{{ $product->color_extra_price }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border-y text-center">{{ $product->quantity }} </td>
                                    <td class="px-4 py-2 border-y">৳{{ number_format($product->base_price, 2) }}</td>
                                    <td class="px-4 py-2 border-y font-semibold">
                                        ৳{{ number_format($product->product_total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    @endif

</div>
