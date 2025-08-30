<section class="bg-white py-10">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Product Image Gallery -->
        <div x-data="{
            images: @js($product->images),
            selectedImage: null
        }" x-init="selectedImage = images[0]">
            <!-- Selected Image -->
            <div class="aspect-square overflow-hidden rounded-xl mb-4">
                <img :src="`/storage/${selectedImage}`" alt="Product Image"
                    class="w-full h-full object-cover transition duration-300" />
            </div>

            <!-- Thumbnails -->
            <div class="grid grid-cols-4 gap-3">
                <template x-for="item in images" :key="item">
                    <img :src="`/storage/${item}`" alt="Thumbnail"
                        class="w-full border-2 border-transparent aspect-square object-cover rounded-xl hover:shadow-lg hover:scale-105 transition cursor-pointer"
                        :class="{ 'ring-2 ring-accent': selectedImage === item }" @click="selectedImage = item" />
                </template>
            </div>
        </div>


        <!-- Product Details -->
        <div class="space-y-6">

            <div>
                <!-- Title -->
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $product->name }}</h1>

                <div class="flex justify-end gap-5">
                    <flux:icon.share variant="outline" class="size-6 text-slate-500 hover:cursor-pointer"/>
                    <flux:icon.heart variant="outline" class="size-6 text-slate-500 hover:cursor-pointer" />
                </div>
            </div>

            <!-- Short Description -->
            <div class="prose text-gray-500 leading-relaxed text-base">
                {!! str($product->highlighted_description)->sanitizeHtml() !!}
            </div>

            <div x-data="productComponent(@js($product), @js($product->sizes), @js($product->colors))" x-cloak class="space-y-6">
                <!-- Price -->
                <div class="flex items-center gap-2 text-xl md:text-2xl font-semibold text-gray-900">
                    <flux:icon.currency-bangladeshi class="size-5 md:size-6" />
                    <span x-text="totalProductPrice"></span>
                </div>

                <!-- Color Options -->
                <div x-show="productColors.length > 0">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Available Colors: <span
                            class="text-sm font-fold text-red-600" x-show="showErrorMessage.colorError">Choose a
                            color</span></h4>
                    <div class="flex flex-wrap gap-3">
                        <template x-for="(color, index) in productColors" :key="index">

                            <div class="flex flex-col items-center justify-start">

                                <button
                                    @click="()=>{
                                    selectedColor = color.color_code; 
                                    extraColorPrice=parseFloat(color.extra_price)
                                }"
                                    class="w-8 h-8 rounded-full border-2 border-white ring-offset-2 hover:cursor-pointer"
                                    :style="{
                                        backgroundColor: color.color_code,
                                        boxShadow: selectedColor === color.color_code ?
                                            `0 0 0 3px ${color.color_code}` : ''
                                    }"
                                    :class="{ 'ring-2': selectedColor === color.color_code }"
                                    :title="color.color_code"></button>
                                <p x-text="'+' + color.extra_price + '৳'" x-show="color.extra_price > 0"
                                    class="text-gray-500 leading-relaxed text-sm"></p>

                            </div>

                        </template>
                    </div>
                </div>

                <!-- Size Options -->
                <div x-show="productSizes.length > 0">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Select Size: <span
                            class="text-sm font-bold text-red-700" x-show="showErrorMessage.sizeError">Choose a
                            size</span></h4>
                    <div class="flex flex-wrap gap-3">
                        <template x-for="size in productSizes" :key="size.id">
                            <div class="flex flex-col items-center justify-start">
                                <button
                                    @click="() => {
                                        selectedSize = size.size.value;
                                        extraSizePrice = parseFloat(size.extra_price) || 0;
                                    }"
                                    class="px-4 py-2 text-sm border rounded hover:bg-gray-100 focus:outline-none focus:ring-2"
                                    :class="{ 'ring-2 ring-accent border-transparent': selectedSize === size.size.value }"
                                    x-text="size.size.value"></button>
                                <p x-text="'+' + size.extra_price + '৳'" x-show="parseFloat(size.extra_price) > 0"
                                    class="text-gray-500 leading-relaxed text-sm"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex flex-col items-start gap-2">
                    <span class="text-sm font-medium text-gray-700">Quantity:</span>
                    <div class="flex items-center border rounded overflow-hidden">
                        <button type="button" class="px-3 py-1 bg-gray-100 text-gray-800"
                            @click="decreaseQuantity()">-</button>
                        <input type="number" min="1" class="w-12 text-center border-l border-r"
                            x-model="productQuantity" />
                        <button type="button" class="px-3 py-1 bg-gray-100 text-gray-800"
                            @click="increaseQuantity()">+</button>
                    </div>
                </div>

                <!-- Order and Add to Cart -->
                <div class="pt-2 flex flex-col gap-3 md:flex-row">

                    @if ($product->out_of_stock)
                        <flux:text color='rose' variant="strong">This product is out of stock</flux:text>
                    @else
                        <flux:button variant="primary" icon="package" class="w-full md:w-auto hover:cursor-pointer"
                            @click="if (validateSelection()) $flux.modal('placing-order').show()">
                            Order Now
                        </flux:button>
                    @endif


                    {{-- <flux:button icon="shopping-cart" class="w-full md:w-auto hover:cursor-pointer">
                        Add to Cart
                    </flux:button> --}}

                </div>


                <flux:modal name="placing-order" class="md:w-full"
                    x-on:order-placed.window="$flux.modal('placing-order').close()">
                    <div class="space-y-6">
                        <div>
                            <flux:heading size="lg">Place Your Order</flux:heading>
                            <flux:text class="mt-2">Enter delivery information to complete your order.</flux:text>
                        </div>

                        <flux:input badge="Required" label="Full Name" placeholder="Enter your name"
                            wire:model.defer="order.customer_name" />

                        <flux:input badge="Required" label="Phone Number" placeholder="01XXXXXXXXX" type="tel"
                            wire:model.defer="order.customer_mobile" />

                        <flux:input badge="Required" label="City" placeholder="Enter your city"
                            wire:model.defer="order.city" />

                        <flux:input label="Upazila" placeholder="Enter your upazila" wire:model.defer="order.upazila" />

                        <flux:input label="Thana" placeholder="Enter your thana" wire:model.defer="order.thana" />

                        <flux:input label="Post Code" placeholder="Enter your post code"
                            wire:model.defer="order.post_code" />

                        <flux:input badge="Required" label="Delivery full Address" placeholder="Enter your address"
                            wire:model.defer="order.address" />


                        <flux:radio.group badge="Required" wire:model.defer='order.selected_shipping_area'
                            label="Select your shipping area">
                            @foreach ($shippingCost as $item)
                                <flux:radio value="{{ $item->id }}"
                                    label="{{ $item->title }}: {{ $item->cost }} ৳"
                                    @click="() => {
                                        selectedShippingArea = $el.getAttribute('data-title');
                                        selectedShipingMethodCost = parseFloat($el.getAttribute('data-cost'));
                                    }"
                                    data-title="{{ $item->title }}" data-cost="{{ $item->cost }}" />
                            @endforeach
                        </flux:radio.group>


                        <flux:select label="Payment Method" wire:model.defer="order.payment_method">
                            <flux:select.option value="cod">Cash on Delivery</flux:select.option>
                        </flux:select>

                        <div>
                            <flux:text x-text='`Product quantity: ${productQuantity}`'></flux:text>

                            <flux:text x-text='`Product price: ${totalProductPrice}৳`'></flux:text>

                            <flux:text x-text='`Seleted shpping cost: ${selectedShipingMethodCost}৳`'>
                            </flux:text>

                            <flux:text x-text='`Extra shpping cost for this product: ${extraProductShippingCost}৳`'>
                            </flux:text>

                            <hr class="text-gray-700" />

                            <div class="flex flex-row justify-between">
                                <flux:text x-text='`TotalCost: ${totalCost}৳`' class="text-lg font-semibold">
                                </flux:text>

                                <flux:error name="placing_order_problem" />

                            </div>
                        </div>

                        <div class="flex">
                            <flux:spacer />
                            <flux:button wire:click="placeOrder" variant="primary"
                                @click="()=> {
                                    $wire.orderedProduct.product_name = product.name
                                    $wire.orderedProduct.quantity = productQuantity
                                    $wire.orderedProduct.selected_color_code = selectedColor
                                    $wire.orderedProduct.color_extra_price = extraColorPrice
                                    $wire.orderedProduct.selected_size = selectedSize
                                    $wire.orderedProduct.size_extra_price = extraSizePrice

                                    $wire.order.extra_shipping_cost = extraProductShippingCost
                                    $wire.order.total_price = totalCost
                                }">
                                Confirm
                                Order</flux:button>
                        </div>


                    </div>
                </flux:modal>

                <flux:modal name="order-placed-succefull" class="md:w-96"
                    x-on:order-placed-succefull.window="$flux.modal('order-placed-succefull').show()">
                    <div class="space-y-6 text-center px-6 py-8">
                        {{-- ✅ Success Icon --}}
                        <div
                            class="mx-auto w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>

                        {{-- ✅ Message --}}
                        <h2 class="text-xl font-semibold text-gray-800">Order Placed Successfully!</h2>

                        <h3 class="text-base font-semibold text-gray-700">Your Order ID: {{ $orderTrackingID }}.</h3>

                        {{-- <p class="text-gray-600 text-sm">
                            You can use the Order ID or
                            <b>{{ $order['customer_mobile'] }}</b> this number to track you order.
                        </p> --}}

                        <p class="text-gray-600 text-sm">
                            Thank you for your purchase. We’ve received your order and will process it soon.
                        </p>

                        {{-- ✅ Button to Close or Redirect --}}
                        <flux:button variant="primary"
                            @click="$flux.modal('order-placed-succefull').close(); window.location.replace('/shop');"
                            class="bg-green-600 hover:cursor-pointer hover:bg-green-700 text-white font-semibold px-4 py-2 rounded">
                            Continue Shopping
                        </flux:button>
                    </div>
                </flux:modal>


            </div>



            <!-- Long Description -->
            @if ($product->details_description)
                <div class="pt-6">
                    <div class="prose">
                        {!! str($product->details_description)->sanitizeHtml() !!}
                    </div>
                </div>
            @endif
        </div>
    </div>


    <!-- Product Reviews & Comments -->
    <div class="py-10 mt-12">
        <div class="container mx-auto px-4 max-w-4xl">
            <!-- Title -->
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Comments</h2>

            <!-- Example Reviews -->
            <div class="space-y-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-800">John Doe</h4>
                        <span class="text-xs text-gray-500">2 days ago</span>
                    </div>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        Great product! Quality is amazing and delivery was fast.
                    </p>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-800">Jane Smith</h4>
                        <span class="text-xs text-gray-500">1 week ago</span>
                    </div>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        Product is good but packaging could be improved.
                    </p>
                </div>
            </div>

            <!-- Add Comment Form -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Add a Comment</h3>

                <form class="space-y-4">

                    <flux:textarea label="Your Comment" placeholder="Write something..." />

                    <div class="flex justify-end">
                        <flux:button variant="primary" class="hover:cursor-pointer">
                            Post Comment
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</section>
