<div x-data="urlHashChangeEvent('#favoriteList', 'favoriteSlider')" class="relative z-50 w-auto h-auto">

    <template x-teleport="body">
        <div x-show="$store.favoriteSlider.slideOverOpen"
            @keydown.window.escape="$store.favoriteSlider.slideOverOpen=false" class="relative z-[99]">
            <div x-show="$store.favoriteSlider.slideOverOpen" x-transition.opacity.duration.600ms
                @click="$store.favoriteSlider.slideOverOpen = false" class="fixed inset-0 bg-black/50"></div>
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
                            <div
                                class="flex flex-col h-full py-5 overflow-y-scroll bg-white border-l shadow-lg border-neutral-100/70">
                                <div class="px-4 sm:px-5">
                                    <div class="flex items-start justify-between pb-1">
                                        <h2 class="text-base font-semibold leading-6 text-gray-900"
                                            id="slide-over-title">Favorite List</h2>
                                        <div class="flex items-center h-auto ml-3">
                                            <button @click="$store.favoriteSlider.slideOverOpen=false"
                                                class="absolute top-0 right-0 z-30 flex items-center justify-center px-3 py-2 mt-4 mr-5 space-x-1 text-xs font-medium uppercase border-neutral-200 text-gray-600 hover:cursor-pointer hover:bg-neutral-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                <span>Close</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative flex-1 px-4 mt-5 sm:px-5">
                                    <div class="absolute inset-0 px-4 sm:px-5">
                                        <div class="relative h-full flex flex-col">

                                            {{-- Start Favorite items --}}

                                            <div class="overflow-scroll pt-4 flex-1">
                                                @auth('customer')
                                                    @forelse($favorites as $product)
                                                        <div class="w-full py-2 border-y border-dashed border-neutral-300">
                                                            <div class="flex flex-row gap-2">
                                                                @if ($product->images && count($product->images) > 0)
                                                                    <img class="aspect-square object-cover" width="80"
                                                                        src="{{ asset('storage/' . $product->images[0]) }}"
                                                                        alt="{{ $product->name }}">
                                                                @else
                                                                    <div
                                                                        class="w-20 h-20 bg-gray-200 flex items-center justify-center">
                                                                        <span class="text-gray-400 text-xs">No image</span>
                                                                    </div>
                                                                @endif

                                                                <div class="flex-1">
                                                                    <div class="flex justify-between items-start">
                                                                        <a href="/shop/{{ $product->slug }}"
                                                                            class="hover:underline">
                                                                            <p class="font-medium text-sm">
                                                                                {{ $product->name }}</p>
                                                                        </a>
                                                                        <flux:icon.x-mark
                                                                            wire:click="removeFavorite({{ $product->id }})"
                                                                            class="size-4 hover:cursor-pointer text-red-500 hover:text-red-700" />
                                                                    </div>
                                                                    <div
                                                                        class="flex items-center w-full justify-between pt-2">
                                                                        <a href="/shop/{{ $product->slug }}" 
                                                                           class="text-xs text-blue-600 hover:text-blue-800">
                                                                            View Product
                                                                        </a>

                                                                        <div class="flex items-center">
                                                                            <flux:icon.currency-bangladeshi
                                                                                class="size-5" />
                                                                            <p class="text-gray-900 font-semibold">
                                                                                {{ number_format($product->base_price, 0) }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div
                                                            class="flex flex-col items-center justify-center py-8 text-gray-500">
                                                            <flux:icon.heart class="size-12 mb-4 text-gray-300" />
                                                            <p class="text-base font-medium">No favorites yet</p>
                                                            <p class="text-sm mt-2">Start adding products to your favorite
                                                                list!</p>
                                                        </div>
                                                    @endforelse
                                                @else
                                                    <div
                                                        class="flex flex-col items-center justify-center py-8 text-gray-500">
                                                        <flux:icon.heart class="size-12 mb-4 text-gray-300" />
                                                        <p class="text-base font-medium">Please sign in</p>
                                                        <p class="text-sm mt-2">Sign in to view your favorite products</p>
                                                        <flux:button variant="primary" size="sm" class="mt-4"
                                                            @click="$store.favoriteSlider.slideOverOpen = false; $dispatch('open-signin-modal')">
                                                            Sign In
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
