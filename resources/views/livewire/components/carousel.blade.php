<div x-data="carouselComponent()" x-init="startAutoScroll" class="relative container mx-auto" style="max-width:1600px;">
    <div class="relative overflow-hidden w-full" style="height: 50vh;">
        <template x-for="(item, index) in carousel" :key="index">
            <div x-show="activeSlide === index" @click="()=> {
                if(item.product_url){
                    window.location.href = item.product_url
                }
            }" 
            :class="item.product_url && 'hover:cursor-pointer'"
            x-transition:enter="transform transition duration-1000 ease-in-out"
                x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transform transition duration-1000 ease-in-out"
                x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="-translate-x-full opacity-0" class="absolute inset-0 bg-cover bg-center"
                :style="`background-image: url(storage/${item.image});`">

                <div class="h-full w-full flex items-center">
                    <div class="container mx-auto px-12 md:px-6">
                        <div class="w-full lg:w-1/2">

                            <p class="text-2xl my-4 p-2 px-4 rounded-xl w-fit bg-white/60" x-show="item.title"
                                x-text="item.title"></p>
                            <a x-show="item.product_url" :href="item.product_url"
                                class="text-xl underline hover:cursor-pointer p-2 px-4 rounded-xl w-fit bg-white/60">
                                View Product
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Buttons -->
        <button @click="prevSlide"
            class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 z-10 bg-white text-black text-2xl p-2 rounded-full shadow hover:bg-black hover:text-white">‹</button>
        <button @click="nextSlide"
            class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 z-10 bg-white text-black text-2xl p-2 rounded-full shadow hover:bg-black hover:text-white">›</button>

        <!-- Indicators -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-3">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="activeSlide = index" :class="activeSlide === index ? 'bg-white' : 'bg-gray-400'"
                    class="w-3 h-3 rounded-full hover:cursor-pointer"></button>
            </template>
        </div>
    </div>
</div>

<script>
    function carouselComponent() {
    return {
        activeSlide: 0,
        slides: {{ count($carousel) }},
        carousel: @js($carousel),
        interval: null,
        startAutoScroll() {
            this.interval = setInterval(() => {
                this.nextSlide()
            }, 5000)
        },
        nextSlide() {
            this.activeSlide = (this.activeSlide + 1) % this.slides
        },
        prevSlide() {
            this.activeSlide = (this.activeSlide - 1 + this.slides) % this.slides
        }
    }
}
</script>