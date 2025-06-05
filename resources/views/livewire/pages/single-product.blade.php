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
            <!-- Title -->
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $product->name }}</h1>

            <!-- Short Description -->
            <div class="prose text-gray-500 leading-relaxed text-base">
                {!! str($product->highlighted_description)->sanitizeHtml() !!}
            </div>

            <div x-data="{
                product: @js($product),
                productQuantity: 1,
                selectedColor: null,
                extraColorPrice: 0,
                selectedSize: null,
                extraSizePrice: 0,
                productSizes: @js($product->sizes),
                productColors: @js($product->colors),
                showErrorMessage: {
                    colorError: false,
                    sizeError: false
                },
                selectedShippingMethod: null,
                selectedShipingMethodCost: 0,
                get extraProductShippingCost() {
                    return parseFloat(this.product.extra_shipping_cost)
                },
                get totalProductPrice() {
                    return (parseFloat(this.product.base_price) + this.extraColorPrice + this.extraSizePrice) * this.productQuantity;
                },
                get totalCost() {
                    return (this.totalProductPrice + this.extraProductShippingCost + this.selectedShipingMethodCost).toFixed(2);
                },
                validateSelection() {
            
                    let isValid = true;
            
                    if (this.productColors.length > 0 && !this.selectedColor) {
                        this.showErrorMessage.colorError = true;
                        isValid = false;
                    } else {
                        this.showErrorMessage.colorError = false;
                    }
            
                    if (this.productSizes.length > 0 && !this.selectedSize) {
                        this.showErrorMessage.sizeError = true;
                        isValid = false;
                    } else {
                        this.showErrorMessage.sizeError = false;
                    }
            
                    return isValid;
                },
                increaseQuantity() {
                    this.productQuantity++;
                },
                decreaseQuantity() {
                    if (this.productQuantity > 1) {
                        this.productQuantity--;
                    }
                },
            }" x-cloak class="space-y-6">
                <!-- Price -->
                <div class="flex items-center gap-2 text-xl md:text-2xl font-semibold text-gray-900">
                    <flux:icon.currency-bangladeshi class="size-5 md:size-6" />
                    <span x-text="totalProductPrice"></span>
                </div>

                <!-- Color Options -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Available Colors: <span
                            class="text-sm font-light text-red-700" x-show="showErrorMessage.colorError">Choose a
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
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Select Size: <span
                            class="text-sm font-light text-red-700" x-show="showErrorMessage.sizeError">Choose a
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

                <!-- Add to Cart -->
                <div class="pt-2 flex flex-col gap-3 md:flex-row">

                    <flux:button variant="primary" icon="package" class="w-full md:w-auto hover:cursor-pointer"
                        @click="if (validateSelection()) $flux.modal('placing-order').show()">
                        Order Now
                    </flux:button>

                    <flux:button icon="shopping-cart" class="w-full md:w-auto hover:cursor-pointer">
                        Add to Cart
                    </flux:button>

                </div>


                <flux:modal name="placing-order" class="md:w-full">
                    <div class="space-y-6">
                        <div>
                            <flux:heading size="lg">Place Your Order</flux:heading>
                            <flux:text class="mt-2">Enter delivery information to complete your order.</flux:text>
                        </div>

                        <flux:input label="Full Name" placeholder="Enter your name"
                            wire:model.defer="order.customer_name" />

                        <flux:input label="Phone Number" placeholder="01XXXXXXXXX" type="tel"
                            wire:model.defer="order.customer_mobile" />

                        <flux:input label="City" placeholder="Enter your city" wire:model.defer="order.city" />

                        <flux:input label="Upazila" placeholder="Enter your upazila" wire:model.defer="order.upazila" />

                        <flux:input label="Thana" placeholder="Enter your thana" wire:model.defer="order.thana" />

                        <flux:input label="Post Code" placeholder="Enter your post code"
                            wire:model.defer="order.address" />

                        <flux:input label="Delivery full Address" placeholder="Enter your address"
                            wire:model.defer="order.address" />


                        <flux:radio.group wire:model="payment" label="Select your payment method" required>
                            @foreach ($shippingCost as $item)
                                <flux:radio value="{{ $item->title }}"
                                    label="{{ $item->title }}: {{ $item->cost }} ৳"
                                    @click="()=>{
                                        selectedShippingMethod = $el.getAttribute('data-title');
                                        selectedShipingMethodCost = parseFloat($el.getAttribute('data-cost'));
                                    }"
                                    data-title="{{ $item->title }}" data-cost="{{ $item->cost }}" />
                            @endforeach
                        </flux:radio.group>

                        <flux:select label="Payment Method" wire:model.defer="order.payment_method">
                            <flux:select.option value="cod">Cash on Delivery</flux:select.option>
                        </flux:select>

                        <div>
                            <flux:text x-text='`Product price: ${totalProductPrice}৳`'></flux:text>

                            <flux:text x-text='`Seleted shpping cost: ${selectedShipingMethodCost}৳`'>
                            </flux:text>

                            <flux:text x-text='`Extra shpping cost for this product: ${extraProductShippingCost}৳`'>
                            </flux:text>

                            <hr class="text-gray-700" />

                            <flux:text x-text='`TotalCost: ${totalCost}৳`'>
                            </flux:text>
                        </div>



                        <div class="flex">
                            <flux:spacer />
                            <flux:button wire:click="placeOrder" variant="primary">Confirm Order</flux:button>
                        </div>


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
</section>
