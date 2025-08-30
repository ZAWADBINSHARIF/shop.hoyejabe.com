<nav x-data="{ open: false }" wire:cloak class="relative w-full px-6 text-gray-700 bg-accent-foreground body-font"
    data-tails-scripts="//unpkg.com/alpinejs" {!! $attributes ?? '' !!}>

    <div class="container flex items-center justify-between py-4 mx-auto max-w-7xl">

        <!-- Logo + Company Name -->
        <a href="/" class="relative z-10 flex items-center space-x-2 text-3xl leading-none text-black select-none">
            @if ($companyDetails?->logo)
                <img src="/storage/{{ $companyDetails->logo }}" width="{{ $logo['width'] }}"
                    height="{{ $logo['height'] }}" />
            @else
                <flux:icon.paw-print class="size-10" />
            @endif
            <span class="hidden min-[400px]:inline text-lg sm:text-3xl">
                {{ $companyDetails->name ?? 'No Name' }}
            </span>
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-6">
            <a href="/"
                class="{{ Route::is('home') ? 'text-gray-900 font-bold' : 'text-gray-600' }} hover:text-gray-900">Home</a>
            <a href="/shop"
                class="{{ Route::is('shop') ? 'text-gray-900 font-bold' : 'text-gray-600' }} hover:text-gray-900">Shop</a>
            <a href="/about"
                class="{{ Route::is('about') ? 'text-gray-900 font-bold' : 'text-gray-600' }} hover:text-gray-900">About</a>
            <a href="/track-order"
                class="{{ Route::is('track-order') ? 'text-gray-900 font-bold' : 'text-gray-600' }} hover:text-gray-900">Track
                Order</a>
        </div>

        <!-- Desktop Actions -->
        <div class="hidden lg:flex items-center gap-4">
            @if ($contact?->mobile_number)
                <flux:tooltip content="Call us" position="bottom">
                    <a href="tel:{{ $contact->mobile_number }}" class="flex items-center gap-2">
                        <flux:icon.headset />
                        {{ $contact->mobile_number }}
                    </a>
                </flux:tooltip>
            @endif

            <!-- Favorite -->
            <div class="relative cursor-pointer" @click="$store.favoriteSlider.slideOverOpen = true">
                <flux:icon.heart class="size-6" />
            </div>

            <!-- Cart -->
            <div class="relative cursor-pointer" @click="$store.cartSlider.slideOverOpen = true">
                <flux:icon.shopping-cart class="size-6" />
                <flux:badge variant="solid" size="sm" class="absolute -top-4 -right-3 text-white h-5">88
                </flux:badge>
            </div>

            <!-- Auth Buttons -->
            <flux:modal.trigger name="signin-modal">
                <flux:button variant="outline">Sign In</flux:button>
            </flux:modal.trigger>
            <flux:modal.trigger name="signup-modal">
                <flux:button variant="primary">Sign Up</flux:button>
            </flux:modal.trigger>

            <flux:avatar @click="$store.profileSlider.slideOverOpen=true" icon="user" class="bg-accent text-white" />

        </div>

        <!-- Mobile Actions -->
        <div class="flex items-center gap-3 lg:hidden">

            <!-- Favorite -->
            <div class="relative cursor-pointer" @click="$store.favoriteSlider.slideOverOpen = true">
                <flux:icon.heart class="size-6" />
            </div>

            <!-- Cart -->
            <div class="relative cursor-pointer" @click="$store.cartSlider.slideOverOpen = true">
                <flux:icon.shopping-cart class="size-6" />
                <flux:badge variant="solid" size="sm" class="absolute -top-4 -right-3 text-white h-5">88
                </flux:badge>
            </div>

            <flux:avatar @click="$store.profileSlider.slideOverOpen=true" icon="user" size="sm"
                class="bg-accent text-white" />



            <!-- Mobile Menu Button -->
            <button @click="open = !open" class="p-2 rounded-lg text-gray-700 hover:bg-gray-200">
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2" class="lg:hidden bg-white px-4 pb-4 space-y-3">
        <a href="/"
            class="block {{ Route::is('home') ? 'text-gray-900 font-semibold' : 'text-gray-600' }}">Home</a>
        <a href="/shop"
            class="block {{ Route::is('shop') ? 'text-gray-900 font-semibold' : 'text-gray-600' }}">Shop</a>
        <a href="/about"
            class="block {{ Route::is('about') ? 'text-gray-900 font-semibold' : 'text-gray-600' }}">About</a>
        <a href="/track-order"
            class="block {{ Route::is('track-order') ? 'text-gray-900 font-semibold' : 'text-gray-600' }}">Track
            Order</a>

        <!-- Mobile Auth & Contact -->
        <div class="pt-3 border-t flex flex-col gap-3">
            @if ($contact?->mobile_number)
                <a href="tel:{{ $contact->mobile_number }}" class="flex items-center gap-2 justify-center">
                    <flux:icon.headset /> {{ $contact->mobile_number }}
                </a>
            @endif

            <flux:modal.trigger name="signin-modal">
                <flux:button variant="outline" class="w-full">Sign In</flux:button>
            </flux:modal.trigger>
            <flux:modal.trigger name="signup-modal">
                <flux:button variant="primary" class="w-full">Sign Up</flux:button>
            </flux:modal.trigger>
        </div>
    </div>

</nav>
