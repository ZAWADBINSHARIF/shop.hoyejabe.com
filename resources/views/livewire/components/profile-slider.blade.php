<div x-data="urlHashChangeEvent('#profile', 'profileSlider')" class="relative z-50 w-auto h-auto">

    <template x-teleport="body">
        <div x-show="$store.profileSlider.slideOverOpen" @keydown.window.escape="$store.profileSlider.slideOverOpen=false"
            class="relative z-[99]">
            <div x-show="$store.profileSlider.slideOverOpen" x-transition.opacity.duration.600ms
                @click="$store.profileSlider.slideOverOpen = false" class="fixed inset-0 bg-black/10"></div>
            <div class="overflow-hidden fixed inset-0">
                <div class="overflow-hidden absolute inset-0">
                    <!-- Remove the pt-11 from the element below, this was needed only for the demo -->
                    <div class="flex fixed inset-y-0 right-0 pl-10 max-w-full">
                        <div x-show="$store.profileSlider.slideOverOpen"
                            @click.away="$store.profileSlider.slideOverOpen = false"
                            x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                            x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                            class="w-screen md:w-[50vw] max-w-full">
                            <div
                                class="flex overflow-y-scroll flex-col py-5 h-full bg-white border-l shadow-lg border-neutral-100/70">
                                <div class="px-4 sm:px-5">
                                    <div class="flex justify-between items-start pb-1">
                                        <h2 class="text-base font-semibold leading-6 text-gray-900"
                                            id="slide-over-title">Profile</h2>
                                        <div class="flex items-center ml-3 h-auto translate-y-1">
                                            <button @click="$store.profileSlider.slideOverOpen=false"
                                                class="flex absolute right-0 z-30 justify-center items-center px-3 py-2 mt-4 mr-5 space-x-1 text-xs font-medium uppercase rounded-md border border-neutral-200 text-neutral-600 hover:bg-neutral-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                <span>Close</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative flex-1 px-4 mt-5 sm:px-5">
                                    <div class="absolute inset-0 px-4 sm:px-5">
                                        <div
                                            class="overflow-hidden relative h-full rounded-md border border-dashed border-neutral-300">

                                            <div x-data="{
                                                tabSelected: 1,
                                                tabId: $id('tabs'),
                                                tabButtonClicked(tabButton) {
                                                    this.tabSelected = tabButton.id.replace(this.tabId + '-', '');
                                                    this.tabRepositionMarker(tabButton);
                                                },
                                                tabRepositionMarker(tabButton) {
                                                    this.$refs.tabMarker.style.width = tabButton.offsetWidth + 'px';
                                                    this.$refs.tabMarker.style.height = tabButton.offsetHeight + 'px';
                                                    this.$refs.tabMarker.style.left = tabButton.offsetLeft + 'px';
                                                },
                                                tabContentActive(tabContent) {
                                                    return this.tabSelected == tabContent.id.replace(this.tabId + '-content-', '');
                                                },
                                                tabButtonActive(tabContent) {
                                                    const tabId = tabContent.id.split('-').slice(-1);
                                                    return this.tabSelected == tabId;
                                                }
                                            }" x-init="tabRepositionMarker($refs.tabButtons.firstElementChild);"
                                                class="relative w-full max-w-sm">

                                                <div x-ref="tabButtons"
                                                    class="relative inline-grid items-center justify-center w-full h-10 grid-cols-3 p-1 text-gray-500 bg-white border border-gray-100 rounded-lg select-none">
                                                    <button :id="$id(tabId)" @click="tabButtonClicked($el);"
                                                        type="button"
                                                        :class="{ 'bg-accent text-white': tabButtonActive($el) }"
                                                        class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">Personal</button>
                                                    <button :id="$id(tabId)" @click="tabButtonClicked($el);"
                                                        type="button"
                                                        :class="{ 'bg-accent text-white': tabButtonActive($el) }"
                                                        class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">Address</button>
                                                    <button :id="$id(tabId)" @click="tabButtonClicked($el);"
                                                        type="button"
                                                        :class="{ 'bg-accent text-white': tabButtonActive($el) }"
                                                        class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">Password</button>
                                                    <div x-ref="tabMarker"
                                                        class="absolute left-0 z-10 w-1/2 h-full duration-300 ease-out"
                                                        x-cloak>
                                                        <div class="w-full h-full bg-gray-100 rounded-md shadow-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="relative flex items-center justify-center w-full p-5 mt-2 text-xs text-gray-400 border rounded-md content border-gray-200/70">

                                                    <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)"
                                                        class="relative">

                                                        <div class="space-y-3">

                                                            <label class="text-sm font-medium text-gray-700">Full
                                                                name</label>
                                                            <flux:input type="text" />


                                                            <div class="space-y-1">
                                                                <label class="text-sm font-medium text-gray-700">Phone
                                                                    Number</label>
                                                                <div class="flex items-center gap-2">
                                                                    <flux:input.group>
                                                                        <flux:input.group.prefix>+88
                                                                        </flux:input.group.prefix>
                                                                        <flux:input type="tel"
                                                                            placeholder="01XXXXXXXXX" maxlength="11"
                                                                            name="phone_number" readonly />
                                                                    </flux:input.group>

                                                                </div>
                                                            </div>

                                                            <!-- OTP Input -->
                                                            <label
                                                                class="text-sm font-medium text-gray-700">Email</label>
                                                            <flux:input type="email" />

                                                            <flux:button variant="primary" color="green"
                                                                class="w-full">
                                                                Save
                                                            </flux:button>

                                                        </div>

                                                    </div>

                                                    <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)"
                                                        class="relative" x-cloak>

                                                        <div class="space-y-3">

                                                            <label
                                                                class="text-sm font-medium text-gray-700">City</label>
                                                            <flux:input type="text" />

                                                            <label
                                                                class="text-sm font-medium text-gray-700">Upazila</label>
                                                            <flux:input type="text" />

                                                            <label
                                                                class="text-sm font-medium text-gray-700">Thana</label>
                                                            <flux:input type="text" />

                                                            <label class="text-sm font-medium text-gray-700">Post
                                                                Code</label>
                                                            <flux:input type="text" />

                                                            <label class="text-sm font-medium text-gray-700">Delivery
                                                                full Address</label>
                                                            <flux:input type="text" />

                                                            <flux:button variant="primary" color="green"
                                                                class="w-full">
                                                                Save
                                                            </flux:button>

                                                        </div>

                                                    </div>

                                                    <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)"
                                                        class="relative" x-cloak>

                                                        <div class="space-y-3">

                                                            <!-- Password -->
                                                            <flux:input label="Password" type="password"
                                                                placeholder="Create a password" viewable />

                                                            <!-- Confirm Password -->
                                                            <flux:input label="Confirm Password" type="password"
                                                                placeholder="Confirm your password" viewable />

                                                            <!-- Submit -->
                                                            <flux:button variant="primary" color="green"
                                                                class="w-full">
                                                                Save
                                                            </flux:button>


                                                        </div>

                                                    </div>

                                                </div>
                                            </div>

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
