@php
    if ($product->discount_percentage <= 0) {
        $product->toggle_discount_price = false;
    }
@endphp

<section class="bg-white py-10" x-on:open-signin-modal.window="$flux.modal('signin-modal').show()">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Product Image Gallery -->
        <div x-data="{
            images: @js($product->images),
            selectedImage: null
        }" x-init="selectedImage = images[0]">
            <!-- Selected Image -->
            <div class="relative aspect-square overflow-hidden rounded-xl mb-4">
                @if ($product->toggle_discount_price)
                    <span
                        class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-lg shadow-md">
                        -{{ (int) $product->discount_percentage }}%
                    </span>
                @endif
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

                <div class="flex justify-end items-center gap-5">
                    <!-- Share Button with Dropdown -->
                    <div x-data="{
                        shareOpen: false,
                        shareUrl: window.location.href,
                        productName: @js($product->name),
                        copied: false,
                        copyLink() {
                            navigator.clipboard.writeText(this.shareUrl);
                            this.copied = true;
                            setTimeout(() => this.copied = false, 2000);
                        },
                        shareOnFacebook() {
                            window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(this.shareUrl), '_blank', 'width=600,height=400');
                        },
                        shareOnTwitter() {
                            window.open('https://twitter.com/intent/tweet?url=' + encodeURIComponent(this.shareUrl) + '&text=' + encodeURIComponent('Check out ' + this.productName), '_blank', 'width=600,height=400');
                        },
                        shareOnWhatsApp() {
                            window.open('https://wa.me/?text=' + encodeURIComponent('Check out ' + this.productName + ': ' + this.shareUrl), '_blank');
                        },
                        shareOnLinkedIn() {
                            window.open('https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent(this.shareUrl), '_blank', 'width=600,height=400');
                        },
                        shareOnMessenger() {
                            // Messenger share works on mobile and desktop
                            window.open('fb-messenger://share?link=' + encodeURIComponent(this.shareUrl), '_blank');
                            // Fallback for web
                            setTimeout(() => {
                                window.open('https://www.facebook.com/dialog/send?app_id=140586622674265&link=' + encodeURIComponent(this.shareUrl) + '&redirect_uri=' + encodeURIComponent(this.shareUrl), '_blank', 'width=600,height=400');
                            }, 500);
                        },
                        shareOnInstagram() {
                            // Instagram doesn't have direct URL sharing, so we copy the link and show instructions
                            this.copyLink();
                            alert('Link copied! Open Instagram and paste the link in your story or bio.');
                        },
                        shareOnTikTok() {
                            // TikTok doesn't have direct URL sharing API, copy link with instructions
                            this.copyLink();
                            alert('Link copied! Open TikTok and paste the link in your video description or bio.');
                        },
                        shareOnTelegram() {
                            window.open('https://t.me/share/url?url=' + encodeURIComponent(this.shareUrl) + '&text=' + encodeURIComponent('Check out ' + this.productName), '_blank');
                        },
                        shareOnPinterest() {
                            // Get the first product image for Pinterest
                            let imageUrl = window.location.origin + '/storage/' + @js($product->images[0] ?? '');
                            window.open('https://pinterest.com/pin/create/button/?url=' + encodeURIComponent(this.shareUrl) + '&media=' + encodeURIComponent(imageUrl) + '&description=' + encodeURIComponent(this.productName), '_blank', 'width=600,height=400');
                        },
                        shareViaEmail() {
                            window.location.href = 'mailto:?subject=' + encodeURIComponent('Check out ' + this.productName) + '&body=' + encodeURIComponent('I thought you might be interested in this product: ' + this.shareUrl);
                        }
                    }" class="relative">
                        <flux:icon.share @click="shareOpen = !shareOpen" variant="outline"
                            class="size-6 text-slate-500 hover:text-slate-700 hover:cursor-pointer transition-colors" />

                        <!-- Share Dropdown -->
                        <div x-show="shareOpen" @click.away="shareOpen = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50 max-h-96 overflow-y-auto"
                            style="display: none;">

                            <div class="sticky top-0 bg-white p-3 border-b border-gray-100">
                                <h3 class="text-sm font-semibold text-gray-700">Share this product</h3>
                            </div>

                            <div class="p-2">
                                <!-- Copy Link -->
                                <button @click="copyLink()"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                    <flux:icon.link class="size-5 text-gray-500" />
                                    <span x-text="copied ? 'Link Copied!' : 'Copy Link'"
                                        class="flex-1 text-left"></span>
                                    <flux:icon.check x-show="copied" class="size-4 text-green-500" />
                                </button>

                                <!-- Facebook -->
                                <button @click="shareOnFacebook()"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                    <svg class="size-5 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                    <span class="flex-1 text-left">Facebook</span>
                                </button>

                                <!-- WhatsApp -->
                                <button @click="shareOnWhatsApp()"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                    <svg class="size-5 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.149-.67.149-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                    </svg>
                                    <span class="flex-1 text-left">WhatsApp</span>
                                </button>

                                <!-- Twitter -->
                                <button @click="shareOnTwitter()"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                    <svg class="size-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                    </svg>
                                    <span class="flex-1 text-left">X (Twitter)</span>
                                </button>

                                <!-- LinkedIn -->
                                <button @click="shareOnLinkedIn()"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                    <svg class="size-5 text-[#0A66C2]" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                    </svg>
                                    <span class="flex-1 text-left">LinkedIn</span>
                                </button>

                                <!-- Messenger -->
                                <button @click="shareOnMessenger()"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                    <svg class="size-5 text-[#0084FF]" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 0C5.373 0 0 4.975 0 11.111c0 3.497 1.745 6.616 4.472 8.652V24l4.086-2.242c1.09.301 2.246.464 3.442.464 6.627 0 12-4.974 12-11.111C24 4.975 18.627 0 12 0zm1.193 14.963l-3.056-3.259-5.963 3.259L10.733 8l3.13 3.259L19.752 8l-6.559 6.963z" />
                                    </svg>
                                    <span class="flex-1 text-left">Messenger</span>
                                </button>

                                <!-- Instagram -->
                                <button @click="shareOnInstagram()"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                    <svg class="size-5" fill="url(#instagram-gradient)" viewBox="0 0 24 24">
                                        <defs>
                                            <linearGradient id="instagram-gradient" x1="0%" y1="100%"
                                                x2="100%" y2="0%">
                                                <stop offset="0%" style="stop-color:#FED576;stop-opacity:1" />
                                                <stop offset="26%" style="stop-color:#F47133;stop-opacity:1" />
                                                <stop offset="61%" style="stop-color:#BC3081;stop-opacity:1" />
                                                <stop offset="100%" style="stop-color:#4F5BD5;stop-opacity:1" />
                                            </linearGradient>
                                        </defs>
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1 1 12.324 0 6.162 6.162 0 0 1-12.324 0zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm4.965-10.405a1.44 1.44 0 1 1 2.881.001 1.44 1.44 0 0 1-2.881-.001z" />
                                    </svg>
                                    <span class="flex-1 text-left">Instagram</span>
                                </button>

                                <!-- TikTok -->
                                <button @click="shareOnTikTok()"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                    <svg class="size-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z" />
                                    </svg>
                                    <span class="flex-1 text-left">TikTok</span>
                                </button>

                                <!-- Telegram -->
                                <button @click="shareOnTelegram()"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                    <svg class="size-5 text-[#229ED9]" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M11.944 0A12 12 0 1 0 24 12a12 12 0 0 0-12.056-12zM16.906 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                                    </svg>
                                    <span class="flex-1 text-left">Telegram</span>
                                </button>

                                <!-- Pinterest -->
                                <button @click="shareOnPinterest()"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                    <svg class="size-5 text-[#BD081C]" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 0a12 12 0 0 0-4.373 23.184c-.054-.486-.104-1.232.021-1.763.113-.49.733-3.105.733-3.105s-.187-.374-.187-.927c0-.869.504-1.517.113-1.517.532 0 .789.39.789.857 0 .522-.333 1.302-.504 2.025-.143.606.303 1.1.898 1.1 1.079 0 1.909-1.137 1.909-2.778 0-1.453-.044-2.625-2.536-2.625-1.729 0-2.743 1.297-2.743 2.636 0 .522.201.082.464 1.285.058.157.029.278-.043.336-.206.587-.665 1.868-.756 2.129-.108.31-.348.237-.806-.026-1.119-.778-1.817-3.22-1.817-5.183 0-4.229 3.073-8.109 8.859-8.109 4.653 0 8.271 3.316 8.271 7.748 0 4.623-2.914 8.34-6.96 8.34-1.36 0-2.638-.706-3.076-1.54 0 0-.673 2.561-.836 3.189-.303 1.166-.12 2.596-1.779 3.607A12 12 0 1 0 12 0z" />
                                    </svg>
                                    <span class="flex-1 text-left">Pinterest</span>
                                </button>

                                <!-- Email -->
                                <button @click="shareViaEmail()"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                    <flux:icon.envelope class="size-5 text-gray-500" />
                                    <span class="flex-1 text-left">Email</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Favorite Button -->
                    <div class="flex items-center gap-2">
                        <flux:icon.heart wire:click="toggleFavorite"
                            variant="{{ $isFavorited ? 'solid' : 'outline' }}"
                            class="size-6 {{ $isFavorited ? 'text-red-500' : 'text-slate-500' }} hover:cursor-pointer hover:scale-110 transition-transform" />
                        <span class="text-sm text-gray-600">
                            @if ($favoriteCount > 0)
                                {{ $favoriteCount }}
                            @endif
                        </span>
                    </div>
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
                    <p class="text-gray-500 line-through text-sm" x-show="product.toggle_discount_price"
                        x-text="(totalProductPrice+totalProductPrice*(product.discount_percentage/100))+'৳'"></p>
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
                            @click="if (validateSelection()) $wire.showOrderModal()">
                            Order Now
                        </flux:button>
                    @endif


                    {{-- <flux:button icon="shopping-cart" class="w-full md:w-auto hover:cursor-pointer">
                        Add to Cart
                    </flux:button> --}}

                </div>


                <flux:modal name="placing-order" class="md:w-full"
                    x-on:order-placed.window="$flux.modal('placing-order').close()"
                    x-on:show-order-modal.window="$flux.modal('placing-order').show()">
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

                        <flux:input label="Upazila" placeholder="Enter your upazila"
                            wire:model.defer="order.upazila" />

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
