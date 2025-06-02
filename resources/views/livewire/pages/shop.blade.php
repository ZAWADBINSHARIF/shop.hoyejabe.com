<section class="bg-white py-8">
    <div class="container mx-auto flex flex-col md:flex-row gap-8">

        <!-- Sidebar for Categories and Filters -->
        <aside class="w-full md:w-1/4 px-4 hidden md:block overflow-y-auto max-h-[80vh]">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Categories</h2>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-black hover:underline">All</a></li>

                    @foreach ($categories as $item)
                        <li><a href="#"
                                class="text-gray-600 hover:text-black hover:underline">{{ $item->name }}</a></li>
                    @endforeach

                </ul>
            </div>
        </aside>

        <livewire:components.category-slider />

        <!-- Main Content: Product Grid -->
        <div class="w-full md:w-3/4">
            <div id="store" class="w-full z-30 top-0 px-6 py-1">
                <div
                    class="w-full container mx-auto flex flex-col md:flex-row gap-3 items-center justify-between mt-0 px-2 py-3">

                    <flux:field class="flex flex-row gap-3 flex-1">
                        <flux:input icon="magnifying-glass" placeholder="Search..." />
                        <flux:button variant="primary" type="submit">Search</flux:button>
                    </flux:field>

                    <div class="flex gap-3">

                        <div class="block md:hidden">
                            <flux:button variant="outline" @click="$store.categorySlider.slideOverOpen=true">
                                <flux:icon.squares-2x2 />
                                <p>Categories</p>
                            </flux:button>
                        </div>

                        <flux:select wire:model="industry" placeholder="Filter...">
                            <flux:select.option>Newest</flux:select.option>
                            <flux:select.option>Price low to high</flux:select.option>
                            <flux:select.option>Price high to low</flux:select.option>
                        </flux:select>

                    </div>

                </div>
            </div>


            <div class="flex flex-wrap overflow-scroll">


                @foreach ($products as $item)
                    <div class="w-full md:w-1/2 xl:w-1/3 p-4 flex flex-col items-center">
                        <a href="product">
                            <img class="transform transition-transform duration-300 rounded-xl hover:scale-105 hover:shadow-lg"
                                src="storage/{{ $item->images[0] }}">
                            <div class="pt-3 flex items-center justify-between">
                                <p class="font-medium text-gray-800">{{ $item->name }}</p>
                            </div>
                            <div class="flex flex-row items-center pt-1">
                                <flux:icon.currency-bangladeshi class="size-6" />
                                <p class="text-gray-900">{{ $item->base_price }}</p>
                                <flux:button icon="shopping-cart" class="hover:cursor-pointer ms-auto">
                                    Add to Cart
                                </flux:button>
                            </div>
                        </a>
                    </div>
                @endforeach


            </div>

            <div class="w-full flex justify-center mt-8 space-x-2">

                {{-- Previous Button --}}
                @if ($products->onFirstPage())
                    <flux:button variant="outline" disabled>
                        <flux:icon.chevron-left />
                    </flux:button>
                @else
                    <flux:button wire:click="previousPage" variant="outline">
                        <flux:icon.chevron-left />
                    </flux:button>
                @endif

                {{-- Page Numbers --}}
                @php
                    $current = $products->currentPage();
                    $last = $products->lastPage();
                    $start = max(1, $current - 2);
                    $end = min($last, $current + 2);
                @endphp

                {{-- First Page --}}
                @if ($start > 1)
                    <flux:button wire:click="gotoPage(1)" :variant="$current === 1 ? 'primary' : 'outline'">
                        1
                    </flux:button>
                    @if ($start > 2)
                        <span class="px-2 text-gray-500">...</span>
                    @endif
                @endif

                {{-- Middle Pages --}}
                @for ($page = $start; $page <= $end; $page++)
                    <flux:button wire:click="gotoPage({{ $page }})"
                        :variant="$current === $page ? 'primary' : 'outline'">
                        {{ $page }}
                    </flux:button>
                @endfor

                {{-- Last Page --}}
                @if ($end < $last)
                    @if ($end < $last - 1)
                        <span class="px-2 text-gray-500">...</span>
                    @endif
                    <flux:button wire:click="gotoPage({{ $last }})"
                        :variant="$current === $last ? 'primary' : 'outline'">
                        {{ $last }}
                    </flux:button>
                @endif

                {{-- Next Button --}}
                @if ($products->hasMorePages())
                    <flux:button wire:click="nextPage" variant="outline">
                        <flux:icon.chevron-right />
                    </flux:button>
                @else
                    <flux:button variant="outline" disabled>
                        <flux:icon.chevron-right />
                    </flux:button>
                @endif

            </div>


        </div>
    </div>
</section>
