<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

const images = [
    { src: '/assets/images/general/mould-1.png', alt: 'Mould 1' },
    { src: '/assets/images/general/mould-2.png', alt: 'Mould 2' }
];

const currentIndex = ref(0);
let interval: number | null = null;
const autoSlideDelay = 4000; // 4 seconds

const nextSlide = () => {
    currentIndex.value = (currentIndex.value + 1) % images.length;
};

const prevSlide = () => {
    currentIndex.value = currentIndex.value === 0 
        ? images.length - 1 
        : currentIndex.value - 1;
};

const goToSlide = (index: number) => {
    currentIndex.value = index;
};

const startAutoSlide = () => {
    if (interval) {
        return;
    }
    
    interval = window.setInterval(() => {
        nextSlide();
    }, autoSlideDelay);
};

const stopAutoSlide = () => {
    if (interval) {
        clearInterval(interval);
        interval = null;
    }
};

onMounted(() => {
    startAutoSlide();
});

onUnmounted(() => {
    stopAutoSlide();
});
</script>

<template>
    <section class="Hero">
        <div class="container-fluid grid lg:grid-cols-2 gap-8 items-center">
            <div class="text space-y-4">
                <h1 class="text-heading-text lg:text-l-text font-bold">Moulds & Machinery</h1>
                <p class="lg:w-[90%]">Plastic, ABS and steel moulds for pavers, balusters, ventilation blocks, cladding and cast-concrete garden pieces - plus the mixers, vibrators and oxide pigments to run a full production line. All held in stock in Nairobi, ready to move.</p>
            </div>

            <div class="images relative overflow-hidden group">
                <!-- Slider -->
                <div 
                    class="slider-container w-full flex transition-transform duration-500 ease-in-out"
                    :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
                    @mouseenter="stopAutoSlide"
                    @mouseleave="startAutoSlide"
                >
                    <img 
                        v-for="(image, index) in images" 
                        :key="index"
                        :src="image.src" 
                        :alt="image.alt"
                        class="w-full h-full shrink-0 object-contain"
                    />
                </div>

                <!-- Previous Button -->
                <button 
                    @click="prevSlide"
                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-all opacity-0 group-hover:opacity-100"
                    aria-label="Previous slide"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Next Button -->
                <button 
                    @click="nextSlide"
                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-all opacity-0 group-hover:opacity-100"
                    aria-label="Next slide"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Dots -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                    <button 
                        v-for="(image, index) in images" 
                        :key="'dot-' + index"
                        @click="goToSlide(index)"
                        class="w-3 h-3 rounded-full transition-all duration-300"
                        :class="currentIndex === index ? 'bg-white w-6' : 'bg-white/40'"
                        :aria-label="`Go to slide ${index + 1}`"
                    />
                </div>

                <!-- Slide Counter -->
                <!-- <div class="absolute top-4 right-4 bg-black/50 text-white text-sm px-3 py-1 rounded-full">
                    {{ currentIndex + 1 }} / {{ images.length }}
                </div> -->
            </div>
        </div>
    </section>
</template>

<style scoped>
.slider-container {
    aspect-ratio: 4/3;
}

button {
    cursor: pointer;
}
</style>