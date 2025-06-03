<div x-data="$store.categorySlider" class="relative z-50 w-auto h-auto">

    <template x-teleport="body">
        <div x-show="$store.categorySlider.slideOverOpen"
            @keydown.window.escape="$store.categorySlider.slideOverOpen=false" class="relative z-[99]">
            <div x-show="$store.categorySlider.slideOverOpen" x-transition.opacity.duration.600ms
                @click="$store.categorySlider.slideOverOpen = false" class="fixed inset-0 bg-black/50"></div>
            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="fixed inset-y-0 left-0 flex max-w-full pr-10">
                        <div x-show="$store.categorySlider.slideOverOpen"
                            @click.away="$store.categorySlider.slideOverOpen = false"
                            x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                            x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                            x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                            class="w-screen max-w-md">
                            <div
                                class="flex flex-col h-full py-5 overflow-y-scroll bg-white border-r shadow-lg border-neutral-100/70">
                                <div class="px-4 sm:px-5">
                                    <div class="flex items-start justify-between pb-1">
                                        <h2 class="text-2xl font-semibold leading-6 text-gray-900"
                                            id="slide-over-title">Categories</h2>
                                        <div class="flex items-center h-auto ml-3">
                                            <flux:button variant="ghost" icon="x-mark" class="hover:cursor-pointer"
                                                @click="$store.categorySlider.slideOverOpen=false">
                                                Close</flux:button>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative flex-1 px-4 mt-5 sm:px-5">
                                    <div class="absolute inset-0 px-4 sm:px-5">
                                        <div class="relative h-full flex flex-col">

                                            <aside class="w-full px-4 overflow-y-scroll">
                                                <div class="mb-6">
                                                    <ul class="space-y-2">
                                                        <li><button wire:click="selectCategory(null)"
                                                                @click="$store.categorySlider.slideOverOpen = false"
                                                                class="text-gray-600 hover:text-black hover:underline">All</button>
                                                        </li>

                                                        @foreach ($categories as $item)
                                                            <li><button
                                                                    wire:click="selectCategory('{{ $item->slug }}')"
                                                                    @click="$store.categorySlider.slideOverOpen = false"
                                                                    class="text-gray-600 hover:text-black hover:underline">{{ $item->name }}</button>
                                                            </li>
                                                        @endforeach

                                                    </ul>
                                                </div>
                                            </aside>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
