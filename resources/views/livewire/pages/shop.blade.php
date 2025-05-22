<section class="bg-white py-8">
    <div class="container mx-auto flex flex-col md:flex-row gap-8">

        <!-- Sidebar for Categories and Filters -->
        <aside class="w-full md:w-1/4 px-4 hidden md:block overflow-y-auto max-h-[80vh]">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Categories</h2>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-black hover:underline">All</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-black hover:underline">Electronics</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-black hover:underline">Clothing</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-black hover:underline">Home & Living</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-black hover:underline">Books</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-black hover:underline">Toys</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-black hover:underline">Beauty</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-black hover:underline">Sports</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-black hover:underline">Automotive</a></li>
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

                @php
                $product = 3
                @endphp


                @for ($i = 0; $i < 12; $i++) <div class="w-full md:w-1/2 xl:w-1/3 p-4 flex flex-col items-center">
                    <a href="product">
                        <img class="transform transition-transform duration-300 rounded-xl hover:scale-105 hover:shadow-lg"
                            src="https://images.unsplash.com/photo-1544787219-7f47ccb76574?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=400&h=400&q=80">
                        <div class="pt-3 flex items-center justify-between">
                            <p class="font-medium text-gray-800">Product Name {{ $i + 1 }}</p>
                        </div>
                        <div class="flex flex-row items-center pt-1">
                            <flux:icon.currency-bangladeshi class="size-6" />
                            <p class="text-gray-900">900</p>
                            <flux:button icon="shopping-cart" class="hover:cursor-pointer ms-auto">
                                Add to Cart
                            </flux:button>
                        </div>
                    </a>
            </div>
            @endfor
 

        </div>

        <div class="flex justify-center mt-6">
            <flux:button variant="outline" class="mx-auto hover:cursor-pointer">Show more products</flux:button>
        </div>
    </div>
    </div>
</section>