<div>
    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full">
                <div class="overflow-hidden border-3 border-dotted border-neutral-300 rounded-lg">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-neutral-50">
                            <tr class="text-neutral-500">
                                <th class="px-5 py-3 text-xs font-medium text-start uppercase">#</th>
                                <th class="px-5 py-3 text-xs font-medium text-start uppercase">Product Name</th>
                                <th class="px-5 py-3 text-xs font-medium text-center uppercase">Size</th>
                                <th class="px-5 py-3 text-xs font-medium text-center uppercase">Color</th>
                                <th class="px-5 py-3 text-xs font-medium text-center uppercase">Quantity</th>
                                <th class="px-5 py-3 text-xs font-medium text-center uppercase">Unit Price</th>
                                <th class="px-5 py-3 text-xs font-medium text-right uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200">

                            @foreach ($orderedProducts as $index => $product)
                                <tr class="text-neutral-800">
                                    <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{ $index + 1 }}</td>
                                    <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">
                                        {{ $product->product_name }}</td>
                                    <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">
                                        <div class="text-center">
                                            {{ $product->selected_size }}
                                        </div>
                                        @if ($product?->size_extra_price > 0)
                                            <div class="text-center">
                                                +৳{{ $product->size_extra_price }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-sm whitespace-nowrap">
                                        <div class="text-center flex justify-center">
                                            <div class="w-5 h-5 rounded-full"
                                                style="
                                        background-color: {{ $product->selected_color_code }}
                                        ">
                                            </div>
                                        </div>
                                        @if ($product?->color_extra_price > 0)
                                            <div class="text-center">
                                                +৳{{ $product->color_extra_price }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-sm whitespace-nowrap text-center">{{ $product->quantity }}
                                    </td>
                                    <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap">
                                        ৳{{ number_format($product->base_price, 2) + $product->color_extra_price + $product->size_extra_price }}
                                    </td>
                                    <td class="px-5 py-4 text-sm font-medium text-right whitespace-nowrap">
                                        ৳{{ number_format($product->product_total_price, 2) }}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
