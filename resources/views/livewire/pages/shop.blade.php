<section class="bg-white py-8">
    <div class="container mx-auto flex flex-col md:flex-row gap-8">

        <!-- Sidebar for Categories and Filters -->
        <aside class="w-full md:w-1/4 px-4 hidden md:block overflow-y-auto max-h-[80vh]">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Categories</h2>
                <ul class="space-y-2">
                    <li><button wire:click="$set('category',null)"
                            class="text-gray-600 hover:text-black hover:underline hover:cursor-pointer">All</button></li>

                    @foreach ($categories as $item)
                        <li>
                            <button wire:click="$set('category', '{{ $item->slug }}')"
                                class="text-gray-600 hover:text-black hover:underline hover:cursor-pointer">
                                {{ $item->name }}
                            </button>
                        </li>
                    @endforeach

                </ul>
            </div>
        </aside>

        <livewire:components.category-slider :categories='$categories' />

        <!-- Main Content: Product Grid -->
        <div class="w-full md:w-3/4">
            <div id="store" class="w-full z-30 top-0 px-6 py-1">
                <div
                    class="w-full container mx-auto flex flex-col md:flex-row gap-3 items-center justify-between mt-0 px-2 py-3">

                    <flux:field class="flex flex-row gap-3 flex-1">
                        <flux:input wire:model.defer='searchInput' wire:keydown.enter='applySearch'
                            icon="magnifying-glass" placeholder="Search..." />
                        <flux:button variant="primary" type="submit" wire:click='applySearch'>Search</flux:button>
                    </flux:field>

                    <div class="flex gap-3">

                        <div class="block md:hidden">
                            <flux:button variant="outline" @click="$store.categorySlider.slideOverOpen=true">
                                <flux:icon.squares-2x2 />
                                <p>Categories</p>
                            </flux:button>
                        </div>

                        <flux:select wire:model.change="sortBy" placeholder="Filter...">
                            <flux:select.option value="newest">Newest</flux:select.option>
                            <flux:select.option value="price_low_high">Price low to high</flux:select.option>
                            <flux:select.option value="price_high_low">Price high to low</flux:select.option>
                        </flux:select>

                    </div>

                </div>
            </div>


            <div class="flex flex-wrap overflow-scroll">

                @foreach ($products as $item)
                    <x-single-product-card :slug="$item->slug" :name="$item->name" :image="$item->images[0]" :basePrice="$item->base_price" />
                @endforeach

                @if (empty($products->items()))
                    <div class="flex justify-center items-center flex-col flex-1 mt-18">
                        <flux:icon.package-open class="size-32 text-gray-600" />
                        <flux:text class="text-lg">No Data found</flux:text>
                    </div>
                @endif


            </div>

            @if (!empty($products->items()) && count($products->items()) >= $perPageItem)

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

            @endif


        </div>
    </div>
</section>
