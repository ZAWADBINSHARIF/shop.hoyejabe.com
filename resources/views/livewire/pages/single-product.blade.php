<section class="bg-white py-10">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Product Image Gallery -->
        <div>
            <div class="aspect-square overflow-hidden rounded-xl mb-4">
                <img id="mainImage"
                    src="https://images.unsplash.com/photo-1544787219-7f47ccb76574?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=400&h=400&q=80"
                    alt="Product Image" class="w-full h-full object-cover transition duration-300" />
            </div>

            <!-- Thumbnails -->
            <div class="grid grid-cols-4 gap-3">
                @for ($i = 1; $i
                <= 4; $i++) <img
                    src="https://images.unsplash.com/photo-1544787219-7f47ccb76574?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=400&h=400&q=80"
                    alt="Thumbnail {{ $i }}"
                    class="w-full aspect-square object-cover rounded-xl hover:shadow-lg hover:scale-105 transition cursor-pointer"
                    onclick="document.getElementById('mainImage').src = this.src" />
                @endfor
            </div>
        </div>

        <!-- Product Details -->
        <div class="space-y-6">
            <!-- Title -->
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Awesome Product Name</h1>

            <!-- Short Description -->
            <p class="text-gray-500 leading-relaxed text-base">
                This is a detailed product description that highlights the product's features, materials, and any other
                relevant information that helps the user make a purchasing decision.
            </p>

            <!-- Price -->
            <div class="flex items-center gap-2 text-xl md:text-2xl font-semibold text-gray-900">
                <flux:icon.currency-bangladeshi class="size-5 md:size-6" />
                <span>900</span>
            </div>

            <!-- Color Options -->
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Available Colors:</h4>
                <div class="flex flex-wrap gap-3">
                    <button
                        class="w-8 h-8 rounded-full bg-red-500 border-2 border-gray-300 hover:ring-2 ring-offset-2 ring-red-500"></button>
                    <button
                        class="w-8 h-8 rounded-full bg-blue-500 border-2 border-gray-300 hover:ring-2 ring-offset-2 ring-blue-500"></button>
                    <button
                        class="w-8 h-8 rounded-full bg-green-500 border-2 border-gray-300 hover:ring-2 ring-offset-2 ring-green-500"></button>
                </div>
            </div>

            <!-- Size Options -->
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Select Size:</h4>
                <div class="flex flex-wrap gap-3">
                    @foreach(['SM', 'MD', 'LG', 'XL', 'XXL'] as $size)
                    <button
                        class="px-4 py-2 text-sm border rounded hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        {{ $size }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Add to Cart -->
            <div class="pt-2">
                <flux:button variant="primary" icon="shopping-cart" class="w-full md:w-auto">
                    Add to Cart
                </flux:button>
            </div>

            <!-- Long Description -->
            <div class="pt-6">
                <h2 class="text-lg font-bold text-gray-800 mb-2">Product's Details</h2>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla dolor facere similique. Facere error,
                    corporis sequi eaque doloremque maxime explicabo nihil perferendis sed aliquid facilis cum at animi
                    rerum. Iure mollitia repellendus omnis ea, tenetur nisi eos suscipit cumque ratione saepe repellat
                    natus dolores praesentium obcaecati error in maiores nihil! Maiores, ipsam molestiae harum alias
                    quisquam ullam minima laborum. Reiciendis dignissimos nisi doloribus, fugit dolore temporibus
                    dolorem doloremque obcaecati. Officia nihil incidunt rem aliquid qui?
                </p>
            </div>
        </div>
    </div>
</section>