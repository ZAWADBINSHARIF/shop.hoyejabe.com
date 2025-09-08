<div x-data="urlHashChangeEvent('#favoriteList', 'favoriteSlider')" class="relative z-50 w-auto h-auto">

    <template x-teleport="body">
        <div x-show="$store.favoriteSlider.slideOverOpen"
            @keydown.window.escape="$store.favoriteSlider.slideOverOpen=false" class="relative z-[99]">
            <div x-show="$store.favoriteSlider.slideOverOpen" x-transition.opacity.duration.600ms
                @click="$store.favoriteSlider.slideOverOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm">
            </div>
            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <div x-show="$store.favoriteSlider.slideOverOpen"
                            @click.away="$store.favoriteSlider.slideOverOpen = false"
                            x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                            x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                            class="w-screen max-w-md">
                            <div class="flex flex-col h-full bg-gradient-to-b from-white to-gray-50 shadow-2xl">
                                {{-- Header --}}
                                <div class="bg-white border-b border-gray-100 px-6 py-5">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-pink-50 rounded-lg">
                                                <flux:icon.heart class="size-5 text-pink-500" />
                                            </div>
                                            <div>
                                                <h2 class="text-lg font-bold text-gray-900">My Favorites</h2>
                                                @auth('customer')
                                                    <p class="text-xs text-gray-500">{{ $favorites->count() }}
                                                        {{ $favorites->count() === 1 ? 'item' : 'items' }}</p>
                                                @endauth
                                            </div>
                                        </div>
                                        <button @click="$store.favoriteSlider.slideOverOpen=false"
                                            class="p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200 group">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor"
                                                class="w-5 h-5 text-gray-400 group-hover:text-gray-600">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="relative flex-1 overflow-hidden">
                                    <div class="absolute inset-0">
                                        <div class="relative h-full flex flex-col">

                                            {{-- Start Favorite items --}}

                                            <div class="overflow-y-auto flex-1 px-6 py-4">
                                                @auth('customer')
                                                    @forelse($favorites as $product)
                                                        <div
                                                            class="group mb-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                                                            <div class="flex p-4">
                                                                {{-- Product Image --}}
                                                                <div class="relative flex-shrink-0">
                                                                    @if ($product->images && count($product->images) > 0)
                                                                        <img class="w-24 h-24 object-cover rounded-lg"
                                                                            src="{{ asset('storage/' . $product->images[0]) }}"
                                                                            alt="{{ $product->name }}">
                                                                    @else
                                                                        <div
                                                                            class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                                                                            <flux:icon.photo class="size-8 text-gray-400" />
                                                                        </div>
                                                                    @endif
                                                                    {{-- Discount Badge --}}
                                                                    @if ($product->discount_percentage > 0)
                                                                        <span
                                                                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                                                            -{{ number_format($product->discount_percentage, 0) }}%
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                {{-- Product Details --}}
                                                                <div class="flex-1 ml-4">
                                                                    <div class="flex justify-between items-start">
                                                                        <div class="flex-1">
                                                                            <a href="/shop/{{ $product->slug }}"
                                                                                class="text-sm font-semibold text-gray-800 hover:text-indigo-600 transition-colors line-clamp-2">
                                                                                {{ $product->name }}
                                                                            </a>
                                                                            @if ($product->category)
                                                                                <p class="text-xs text-gray-500 mt-1">
                                                                                    {{ $product->category->name }}</p>
                                                                            @endif
                                                                        </div>
                                                                        <button
                                                                            wire:click="removeFavorite({{ $product->id }})"
                                                                            class="ml-2 p-1.5 rounded-lg hover:bg-red-50 transition-colors group/btn">
                                                                            <flux:icon.trash
                                                                                class="size-4 text-gray-400 group-hover/btn:text-red-500 transition-colors" />
                                                                        </button>
                                                                    </div>

                                                                    {{-- Price and Action --}}
                                                                    <div class="flex items-center justify-between mt-3">
                                                                        <div>
                                                                            @if ($product->discount_percentage > 0)
                                                                                <div class="flex items-center gap-2">
                                                                                    <span
                                                                                        class="text-lg font-bold text-gray-900">
                                                                                        ৳{{ number_format($product->base_price + $product->base_price * ($product->discount_percentage / 100), 0) }}
                                                                                    </span>
                                                                                    <span
                                                                                        class="text-sm text-gray-400 line-through">
                                                                                        ৳{{ number_format($product->base_price, 0) }}
                                                                                    </span>
                                                                                </div>
                                                                            @else
                                                                                <span
                                                                                    class="text-lg font-bold text-gray-900">
                                                                                    ৳{{ number_format($product->base_price, 0) }}
                                                                                </span>
                                                                            @endif
                                                                        </div>

                                                                        <a href="/shop/{{ $product->slug }}"
                                                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition-colors">
                                                                            <flux:icon.shopping-bag class="size-3" />
                                                                            Shop Now
                                                                        </a>
                                                                    </div>

                                                                    {{-- Stock Status --}}
                                                                    @if ($product->track_quantity && $product->quantity <= 5)
                                                                        <div class="mt-2 flex items-center gap-1">
                                                                            <flux:icon.exclamation-circle
                                                                                class="size-3 text-amber-500" />
                                                                            <span
                                                                                class="text-xs text-amber-600 font-medium">
                                                                                Only {{ $product->quantity }} left in stock
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="flex flex-col items-center justify-center py-16 px-8">
                                                            <div class="p-4 bg-pink-50 rounded-full mb-4">
                                                                <flux:icon.heart class="size-12 text-pink-300" />
                                                            </div>
                                                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Your
                                                                favorites list is empty</h3>
                                                            <p class="text-sm text-gray-500 text-center mb-6">
                                                                Start adding products you love to quickly access them later!
                                                            </p>
                                                            <a href="/shop"
                                                                @click="$store.favoriteSlider.slideOverOpen = false"
                                                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                                                                <flux:icon.shopping-bag class="size-4" />
                                                                Browse Products
                                                            </a>
                                                        </div>
                                                    @endforelse

                                                    {{-- Continue Shopping Button --}}
                                                    @if ($favorites->count() > 0)
                                                        <flux:button @click="$store.favoriteSlider.slideOverOpen = false"
                                                            icon="arrow-left"
                                                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-gray-900 hover:bg-gray-800 hover:cursor-pointer text-white font-medium rounded-lg transition-colors">
                                                            Continue Shopping
                                                        </flux:button>
                                                    @endif
                                                @else
                                                    <div class="flex flex-col items-center justify-center py-16 px-8">
                                                        <div class="p-4 bg-gray-100 rounded-full mb-4">
                                                            <flux:icon.lock-closed class="size-12 text-gray-400" />
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Sign in to view
                                                            favorites</h3>
                                                        <p class="text-sm text-gray-500 text-center mb-6">
                                                            Create an account or sign in to save and manage your favorite
                                                            products
                                                        </p>
                                                        <flux:button variant="primary" size="base"
                                                            @click="$store.favoriteSlider.slideOverOpen = false; $dispatch('open-signin-modal')"
                                                            class="gap-2">
                                                            <flux:icon.user class="size-4" />
                                                            Sign In Now
                                                        </flux:button>
                                                    </div>
                                                @endauth
                                            </div>

                                            {{-- end Favorite items --}}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
