@props([
    'toggleDiscountPrice' => false,
    'slug',
    'name',
    'image',
    'basePrice',
    'className' => 'w-full md:w-1/2 xl:w-1/3 p-4 flex flex-col items-center',
    'discountPercentage',
])
@php
    if ($discountPercentage <= 0) {
        $toggleDiscountPrice = false;
    }
@endphp
<div class="{{ $className }}">
    <a href="shop/{{ $slug }}" class="w-full">
        <div class="relative transform transition-transform hover:scale-105 hover:shadow-lg">
            <!-- Discount Badge -->
            @if ($toggleDiscountPrice)
                <span
                    class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-lg shadow-md">
                    -{{ (int) $discountPercentage }}%
                </span>
            @endif

            <!-- Product Image -->
            <img class="w-full duration-300 rounded-xl" src="storage/{{ $image }}" alt="{{ $name }}">
        </div>

        <!-- Product Name -->
        <div class="pt-3 flex items-center justify-between">
            <p class="font-medium text-gray-800">{{ $name }}</p>
        </div>

        <!-- Price Section -->
        <div class="flex flex-row items-center pt-1 gap-2">
            {{-- <flux:icon.currency-bangladeshi class="size-6" /> --}}

            <div class="flex flex-row gap-1">
                @if ($toggleDiscountPrice)
                    <!-- Old Price -->
                    @php
                        $oldprice = $basePrice + ($basePrice * $discountPercentage) / 100;
                    @endphp
                    <p class="text-gray-500 line-through text-sm">{{ number_format($oldprice, 0) }}৳</p>

                    <!-- New Discounted Price -->
                    <p class="text-gray-900 font-semibold text-md">{{ number_format($basePrice, 0) }}৳</p>
                @else
                    <!-- Regular Price -->
                    <p class="text-gray-900 font-semibold text-md">{{ number_format($basePrice, 0) }}৳</p>
                @endif
            </div>

            <!-- See Details Button -->
            <flux:button icon="eye" class="hover:cursor-pointer ms-auto">
                See details
            </flux:button>
        </div>
    </a>
</div>
