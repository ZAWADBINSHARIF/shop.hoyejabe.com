<section class="bg-white py-10">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Product Image Gallery -->
        <div x-data="{
            images: @js($product->images),
            selectedImage: null
        }" x-init="selectedImage = images[0]">
            <!-- Selected Image -->
            <div class="relative aspect-square overflow-hidden rounded-xl mb-4">
                <span
                    class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-lg shadow-md">
                    -15%
                </span>
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
                    <flux:icon.share variant="outline" class="size-6 text-slate-500 hover:cursor-pointer" />
                    <flux:icon.heart 
                        wire:click="toggleFavorite"
                        variant="{{ $isFavorited ? 'solid' : 'outline' }}" 
                        class="size-6 {{ $isFavorited ? 'text-red-500' : 'text-slate-500' }} hover:cursor-pointer hover:scale-110 transition-transform" />
                </div>
            </div>

            <!-- Short Description -->
            <div class="prose text-gray-500 leading-relaxed text-base">
                {!! str($product->highlighted_description)->sanitizeHtml() !!}
            </div>

            <div x-data="productComponent(@js($product), @js($product->sizes), @js($product->colors))" x-cloak class="space-y-6">
                <!-- Price -->
                <div class="flex text-xl md:text-2xl font-semibold text-gray-900 gap-1">

                    <!-- Old Price -->
                    <p class="text-gray-500 line-through text-sm"
                        x-text="(totalProductPrice+totalProductPrice*0.15)+'৳'"></p>

                    {{-- Discount Price  --}}
                    <p class="text-gray-900" x-text="totalProductPrice+'৳'"></p>

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
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Customer Reviews & Comments</h2>

            <!-- Success Message -->
            @if (session()->has('comment_message'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <flux:icon.check-circle class="size-5 text-green-600 mr-2" />
                        <p class="text-green-800">{{ session('comment_message') }}</p>
                    </div>
                </div>
            @endif

            <!-- Comments List -->
            <div class="space-y-6 mb-8">
                @forelse($comments as $comment)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="font-semibold text-gray-800">{{ $comment->customer_display_name }}</h4>
                                    @if ($comment->is_verified_purchase)
                                        <flux:badge variant="subtle" color="green" size="sm">
                                            <flux:icon.check-badge class="size-3" />
                                            Verified Purchase
                                        </flux:badge>
                                    @endif
                                </div>
                                @if ($comment->rating)
                                    <div class="flex items-center gap-1 mt-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $comment->rating)
                                                <flux:icon.star variant="solid" class="size-4 text-yellow-500" />
                                            @else
                                                <flux:icon.star variant="outline" class="size-4 text-gray-300" />
                                            @endif
                                        @endfor
                                        <span class="text-sm text-gray-600 ml-1">({{ $comment->rating }}/5)</span>
                                    </div>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-700 text-sm leading-relaxed">
                            {{ $comment->comment }}
                        </p>
                    </div>
                @empty
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                        <p class="text-gray-500">No reviews yet. Be the first to share your thoughts!</p>
                    </div>
                @endforelse
            </div>

            <!-- Add Comment Form -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Write a Review</h3>

                @auth('customer')
                    <form wire:submit.prevent="submitComment" class="space-y-4">
                        <!-- Rating Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
                            <div class="flex items-center gap-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" wire:click="$set('newRating', {{ $i }})"
                                        class="focus:outline-none">
                                        @if ($i <= $newRating)
                                            <flux:icon.star variant="solid"
                                                class="size-6 text-yellow-500 hover:scale-110 transition-transform" />
                                        @else
                                            <flux:icon.star variant="outline"
                                                class="size-6 text-gray-300 hover:text-yellow-500 hover:scale-110 transition-transform" />
                                        @endif
                                    </button>
                                @endfor
                                <span class="text-sm text-gray-600 ml-2">
                                    @switch($newRating)
                                        @case(1)
                                            Poor
                                        @break

                                        @case(2)
                                            Fair
                                        @break

                                        @case(3)
                                            Good
                                        @break

                                        @case(4)
                                            Very Good
                                        @break

                                        @case(5)
                                            Excellent
                                        @break
                                    @endswitch
                                </span>
                            </div>
                            <flux:error name="newRating" />
                        </div>

                        <!-- Comment Textarea -->
                        <flux:textarea wire:model="newComment" label="Your Comment"
                            placeholder="Share your experience with this product..." rows="4" />

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <flux:button icon="chat-bubble-left-right" type="submit" variant="primary"
                                class="hover:cursor-pointer">
                                Post Comment
                            </flux:button>
                        </div>
                    </form>
                @else
                    <!-- Sign In Prompt -->
                    <div class="text-center py-4">
                        <p class="text-gray-600 mb-4">Please sign in to write a review</p>
                        <flux:modal.trigger name="signin-modal">
                            <flux:button variant="primary" class="hover:cursor-pointer">
                                <flux:icon.user class="size-5" />
                                Sign In to Comment
                            </flux:button>
                        </flux:modal.trigger>
                    </div>
                @endauth

                <!-- Auth Error Message -->
                @error('auth')
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <flux:icon.exclamation-triangle class="size-5 text-yellow-600 mr-2" />
                            <p class="text-yellow-800">{{ $message }}</p>
                        </div>
                    </div>
                @enderror
            </div>
        </div>
    </div>


</section>
