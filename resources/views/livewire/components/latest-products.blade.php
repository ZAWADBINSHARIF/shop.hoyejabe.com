<section class="bg-white py-8">

    <div class="container mx-auto flex items-center flex-wrap pt-4 pb-12">

        <nav id="store" class="w-full z-30 top-0 px-6 py-1">
            <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-2 py-3">

                <a class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-xl "
                    href="/shop">
                    Store
                </a>

                <div class="flex items-center" id="store-nav-content">

                    <flux:field class="flex flex-row gap-3">
                        <flux:input wire:model.defer='searchInput' wire:keydown.enter='applySearch'
                            icon="magnifying-glass" placeholder="Search..." />
                        <flux:button variant="outline" type="submit" wire:click='applySearch'
                            class="cursor-none hover:cursor-pointer">
                            Search</flux:button>
                    </flux:field>

                </div>
            </div>
        </nav>

        @foreach ($latestProducts as $item)
            <div class="w-full md:w-1/3 xl:w-1/4 p-6 flex flex-col items-center">
                <a href="product">
                    <img class="hover:grow hover:shadow-lg rounded-xl" src="storage/{{ $item->images[0] }}">
                    <div class="pt-3 flex items-center justify-between">
                        <p class="">{{ $item->name }}</p>
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

        @if (count($latestProducts) === 0)
            <div class="flex justify-center items-center flex-col flex-1 mt-18">
                <flux:icon.package-open class="size-32 text-gray-600" />
                <flux:text class="text-lg">No Data found</flux:text>
            </div>
        @endif

    </div>

    @if (count($latestProducts) !== 0)
        <div class="flex justify-center">
            <a href="/shop">
                <flux:button variant="outline" class="mx-auto hover:cursor-pointer">Show more products</flux:button>
            </a>
        </div>
    @endif

</section>
