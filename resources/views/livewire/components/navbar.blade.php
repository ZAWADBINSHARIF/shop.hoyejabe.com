<nav x-data wire:cloak class="relative w-full px-8 text-gray-700 bg-accent-foreground body-font"
    data-tails-scripts="//unpkg.com/alpinejs" {!! $attributes ?? '' !!}>
    <div class="container flex flex-col flex-wrap items-center justify-between py-5 mx-auto md:flex-row max-w-7xl">
        <a href="/"
            class="relative z-10 flex items-center w-auto text-3xl leading-none text-black select-none space-x-2">

            @if ($companyDetails?->logo)
                <img src="/storage/{{ $companyDetails->logo }}" width="{{ $logo['width'] }}"
                    height="{{ $logo['height'] }}" />
            @else
                <flux:icon.paw-print class="size-12" />
            @endif

            <p>{{ $companyDetails->name ?? 'No Name' }}</p>
        </a>

        <nav
            class="top-0 left-0 z-0 flex items-center justify-center w-full h-full py-5 -ml-0 space-x-5 text-base md:-ml-5 md:py-0 md:absolute">
            <a href="/" x-data="{
                active: {{ Route::is('home') ? 'true' : 'false' }},
                hover: false
            }"
                class="relative font-medium leading-6 transition duration-150 ease-out hover:text-gray-900"
                :class="active === true ? 'text-gray-900' : 'text-gray-600'" @mouseenter="hover = true"
                @mouseleave="hover = false">

                <span class="block">Home</span>
                <span class="absolute bottom-0 left-0 inline-block w-full h-0.5 -mb-1 overflow-hidden">
                    <span x-show="hover || active"
                        class="absolute inset-0 inline-block w-full h-full transform bg-gray-900"
                        x-transition:enter="transition ease duration-200" x-transition:enter-start="scale-0"
                        x-transition:enter-end="scale-100" x-transition:leave="transition ease-out duration-300"
                        x-transition:leave-start="scale-100" x-transition:leave-end="scale-0"></span>
                </span>
            </a>

            <a href="/shop" x-data="{
                active: {{ Route::is('shop') ? 'true' : 'false' }},
                hover: false
            }"
                class="relative font-medium leading-6 transition duration-150 ease-out hover:text-gray-900"
                :class="active === true ? 'text-gray-900' : 'text-gray-600'" @mouseenter="hover = true"
                @mouseleave="hover = false">

                <span class="block">Shop</span>
                <span class="absolute bottom-0 left-0 inline-block w-full h-0.5 -mb-1 overflow-hidden">
                    <span x-show="hover || active"
                        class="absolute inset-0 inline-block w-full h-full transform bg-gray-900"
                        x-transition:enter="transition ease duration-200" x-transition:enter-start="scale-0"
                        x-transition:enter-end="scale-100" x-transition:leave="transition ease-out duration-300"
                        x-transition:leave-start="scale-100" x-transition:leave-end="scale-0"></span>
                </span>
            </a>

            <a href="/about" x-data="{
                active: {{ Route::is('about') ? 'true' : 'false' }},
                hover: false
            }"
                class="relative font-medium leading-6 transition duration-150 ease-out hover:text-gray-900"
                :class="active === true ? 'text-gray-900' : 'text-gray-600'" @mouseenter="hover = true"
                @mouseleave="hover = false">

                <span class="block">About</span>
                <span class="absolute bottom-0 left-0 inline-block w-full h-0.5 -mb-1 overflow-hidden">
                    <span x-show="hover || active"
                        class="absolute inset-0 inline-block w-full h-full transform bg-gray-900"
                        x-transition:enter="transition ease duration-200" x-transition:enter-start="scale-0"
                        x-transition:enter-end="scale-100" x-transition:leave="transition ease-out duration-300"
                        x-transition:leave-start="scale-100" x-transition:leave-end="scale-0"></span>
                </span>
            </a>

            {{-- <a href="/contact-us" x-data="{
                active: {{ Route::is('contact-us') ? 'true' : 'false' }},
                hover: false
            }"
                class="relative font-medium leading-6 transition duration-150 ease-out hover:text-gray-900"
                :class="active === true ? 'text-gray-900' : 'text-gray-600'" @mouseenter="hover = true"
                @mouseleave="hover = false">

                <span class="block">Contact Us</span>
                <span class="absolute bottom-0 left-0 inline-block w-full h-0.5 -mb-1 overflow-hidden">
                    <span x-show="hover || active"
                        class="absolute inset-0 inline-block w-full h-full transform bg-gray-900"
                        x-transition:enter="transition ease duration-200" x-transition:enter-start="scale-0"
                        x-transition:enter-end="scale-100" x-transition:leave="transition ease-out duration-300"
                        x-transition:leave-start="scale-100" x-transition:leave-end="scale-0"></span>
                </span>
            </a> --}}

        </nav>

        <div>
            <div
                class="relative z-10 inline-flex items-center space-x-3 md:mt-0 md:ml-5 lg:justify-end hover:cursor-pointer">

                @if ($contact?->mobile_number)
                    <flux:button class="hover:cursor-pointer">
                        <a href="tel:{{ $contact->mobile_number }}"
                            class="flex flex-row items-center justify-center gap-2">
                            <flux:icon.headset />
                            <flux:text class="text-black hidden md:block">{{ $contact->mobile_number }}
                            </flux:text>
                            <flux:text class="text-black block md:hidden">Call Us</flux:text>
                        </a>
                    </flux:button>
                @endif

            </div>
            {{-- <div class="relative z-10 inline-flex items-center space-x-3 mt-3 md:mt-0 md:ml-5 lg:justify-end hover:cursor-pointer"
                @click="$store.cartSlider.slideOverOpen=true">
                <flux:icon.shopping-cart class="size-6" />
                <flux:badge variant="solid" color="indigo" size="sm" class="absolute -top-4 text-white h-5">88
                </flux:badge>
            </div> --}}
        </div>

    </div>
</nav>
