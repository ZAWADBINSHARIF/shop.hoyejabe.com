<section class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-5xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-8">My Orders</h2>

        @if ($orders->count() > 0)
            @foreach ($orders as $order)
                <div
                    class="mb-8 rounded-2xl bg-white shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-300">

                    {{-- Order Header --}}
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-200 p-6 gap-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Order #{{ $order->order_tracking_id }}</h3>
                            <p class="text-sm text-gray-600">Placed on {{ $order->created_at->format('M d, Y') }}</p>
                        </div>

                        <div class="flex flex-col items-end gap-3">

                            <div class="flex gap-2">
                                {{-- View Invoice --}}
                                <a href="{{ route('invoice.view', $order->order_tracking_id) }}" target="_blank">
                                    <flux:button variant="ghost" class="hover:cursor-pointer">
                                        <span>
                                            View invoice
                                        </span>
                                        <flux:icon icon="eye" class="size-5" />
                                    </flux:button>
                                </a>

                                {{-- Download Invoice --}}
                                <a href="{{ route('invoice.download', $order->order_tracking_id) }}" target="_blank">
                                    <flux:button class="hover:cursor-pointer">
                                        <span>
                                            Download invoice
                                        </span>
                                        <flux:icon icon="arrow-down" class="size-5" />
                                    </flux:button>
                                </a>
                            </div>

                            <flux:badge variant="solid" color="indigo" class="block">
                                {{ ucwords((string) $order->order_status->value) ?? 'Processing' }}
                            </flux:badge>

                        </div>
                    </div>

                    {{-- Order Info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 text-sm text-gray-700">
                        <div>
                            <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Mobile:</strong> {{ $order->customer_mobile }}</p>
                            <p><strong>Address:</strong> {{ $order->address }}, {{ $order->city }}</p>
                        </div>
                        <div>
                            <p><strong>Shipping Area:</strong> {{ $order->shipping ? $order->shipping->title : 'N/A' }}
                            </p>
                            <p><strong>Shipping Cost:</strong> ৳{{ number_format($order->shipping_cost, 2) }}</p>
                            <p><strong>Total:</strong> <span
                                    class="font-bold text-indigo-600">৳{{ number_format($order->total_price, 2) }}</span>
                            </p>
                        </div>
                    </div>

                    {{-- Ordered Products --}}
                    <div class="p-6 border-t border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Ordered Products</h4>
                        <div class="overflow-x-auto">
                            <x-ordered-product-table :orderedProducts="$order->orderedProducts" />
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-xl shadow-md border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-700">No Orders Found</h3>
                <p class="text-gray-500 mt-2">Looks like you haven’t placed any orders yet.</p>
                <a href="/shop"
                    class="mt-6 inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg shadow hover:bg-indigo-700 transition">
                    Shop Now
                </a>
            </div>
        @endif
    </div>
</section>
