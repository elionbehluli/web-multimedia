<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import PixiBackground from '@/Components/PixiBackground.vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const currentSlide = ref(0);
const touchStartX = ref(0);
const isHoveringZoom = ref(false);
const zoomBackgroundPosition = ref('0% 0%');
const zoomContainer = ref(null);

const handleMouseEnter = () => {
    isHoveringZoom.value = true;
};

const handleMouseLeave = () => {
    isHoveringZoom.value = false;
};

const handleMouseMove = (e) => {
    if (!zoomContainer.value) return;
    
    const rect = zoomContainer.value.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;
    
    zoomBackgroundPosition.value = `${x}% ${y}%`;
};

const nextSlide = () => {
    if (props.product.images.length > 0) {
        currentSlide.value = (currentSlide.value + 1) % props.product.images.length;
    }
};

const prevSlide = () => {
    if (props.product.images.length > 0) {
        currentSlide.value = (currentSlide.value - 1 + props.product.images.length) % props.product.images.length;
    }
};

const handleTouchStart = (e) => {
    touchStartX.value = e.touches[0].clientX;
};

const handleTouchEnd = (e) => {
    const touchEndX = e.changedTouches[0].clientX;
    const diff = touchStartX.value - touchEndX;
    if (Math.abs(diff) > 50) {
        if (diff > 0) nextSlide();
        else prevSlide();
    }
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};
</script>

<template>
    <Head :title="`${product.title} - Multimedia Pro`" />
    
    <div class="min-h-screen bg-[#0a0a0c] text-white selection:bg-purple-600 selection:text-white font-sans">
        <!-- PixiJS Animated Background -->
        <PixiBackground />

        <div class="relative z-10">
            <!-- Navigation -->
            <nav class="flex items-center justify-between px-8 py-6 backdrop-blur-md bg-black/20 border-b border-white/5 sticky top-0 z-50">
                <Link href="/" class="flex items-center gap-2 group">
                    <div class="size-10 bg-gradient-to-tr from-purple-600 to-blue-500 rounded-lg flex items-center justify-center shadow-lg shadow-purple-500/20 group-hover:scale-110 transition-transform">
                        <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white/90">Back to <span class="text-purple-500">Collection</span></span>
                </Link>
            </nav>

            <main class="max-w-7xl mx-auto px-6 py-12 lg:py-24">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                    
                    <!-- LEFT: Image Slider -->
                    <div class="relative group">
                        <div 
                            ref="zoomContainer"
                            class="aspect-square max-w-sm mx-auto bg-[#121215] rounded-[2rem] overflow-hidden border border-white/5 shadow-2xl relative cursor-zoom-in"
                            @touchstart="handleTouchStart"
                            @touchend="handleTouchEnd"
                            @mouseenter="handleMouseEnter"
                            @mouseleave="handleMouseLeave"
                            @mousemove="handleMouseMove"
                        >
                            <!-- Images -->
                            <div 
                                class="flex h-full transition-transform duration-700 ease-in-out" 
                                :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
                            >
                                <div 
                                    v-for="(image, index) in product.images" 
                                    :key="image.id"
                                    class="min-w-full h-full relative"
                                >
                                    <img 
                                        :src="`/${image.file_path}`" 
                                        :alt="product.title"
                                        class="w-full h-full object-cover"
                                    />
                                    <!-- Multimedia Overlay Effect -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0c] via-transparent to-transparent opacity-60"></div>
                                </div>
                                <div v-if="product.images.length === 0" class="min-w-full h-full flex items-center justify-center bg-gray-900">
                                    <svg class="size-32 text-white/5" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                </div>
                            </div>

                            <!-- Slider Controls -->
                            <div v-if="product.images.length > 1" class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3 z-20">
                                <button 
                                    v-for="(_, index) in product.images" 
                                    :key="index"
                                    @click="currentSlide = index"
                                    class="size-2.5 rounded-full transition-all duration-300"
                                    :class="currentSlide === index ? 'bg-purple-500 w-8' : 'bg-white/20 hover:bg-white/40'"
                                ></button>
                            </div>

                            <!-- Arrows -->
                            <button 
                                v-if="product.images.length > 1"
                                @click="prevSlide"
                                class="absolute left-6 top-1/2 -translate-y-1/2 size-12 rounded-full bg-black/40 backdrop-blur-md border border-white/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-purple-600/40"
                            >
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            </button>
                            <button 
                                v-if="product.images.length > 1"
                                @click="nextSlide"
                                class="absolute right-6 top-1/2 -translate-y-1/2 size-12 rounded-full bg-black/40 backdrop-blur-md border border-white/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-purple-600/40"
                            >
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </button>
                        </div>

                        <!-- Floating Badge -->
                        <div class="absolute -top-6 -right-6 px-6 py-4 bg-gradient-to-tr from-purple-600 to-blue-500 rounded-3xl shadow-2xl shadow-purple-500/30 transform rotate-12 group-hover:rotate-0 transition-transform duration-500 z-30">
                            <span class="text-2xl font-black">{{ formatPrice(product.price) }}</span>
                        </div>

                        <!-- Zoom Lens Circle -->
                        <div 
                            v-if="isHoveringZoom && product.images.length > 0"
                            class="fixed top-1/2 -translate-y-1/2 left-[calc(50%+2rem)] w-[600px] h-[600px] rounded-full border-4 border-purple-500/50 shadow-2xl z-[100] bg-no-repeat pointer-events-none hidden lg:block overflow-hidden"
                            :style="{
                                backgroundImage: `url(/${product.images[currentSlide].file_path})`,
                                backgroundPosition: zoomBackgroundPosition,
                                backgroundSize: '250%'
                            }"
                        >
                            <div class="absolute inset-0 ring-inset ring-1 ring-white/20 rounded-full"></div>
                        </div>
                    </div>

                    <!-- RIGHT: Content -->
                    <div class="flex flex-col h-full">
                        <div class="mb-8">
                            <span class="text-purple-500 font-bold tracking-widest uppercase text-sm mb-4 block">Product Detail</span>
                            <h1 class="text-4xl lg:text-6xl font-extrabold tracking-tighter mb-6 bg-clip-text text-transparent bg-gradient-to-b from-white to-gray-400">
                                {{ product.title }}
                            </h1>
                            <p class="text-gray-400 text-lg lg:text-xl leading-relaxed font-light mb-12">
                                {{ product.description }}
                            </p>
                        </div>

                        <!-- Features Grid -->
                        <div class="grid grid-cols-2 gap-4 mb-12">
                            <div class="p-6 bg-white/5 rounded-2xl border border-white/5 hover:border-purple-500/30 transition-colors">
                                <div class="size-8 bg-purple-500/20 rounded-lg flex items-center justify-center mb-4 text-purple-400">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                </div>
                                <h4 class="font-bold mb-1">Performance</h4>
                                <p class="text-xs text-gray-500">Optimized for speed</p>
                            </div>
                            <div class="p-6 bg-white/5 rounded-2xl border border-white/5 hover:border-blue-500/30 transition-colors">
                                <div class="size-8 bg-blue-500/20 rounded-lg flex items-center justify-center mb-4 text-blue-400">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                </div>
                                <h4 class="font-bold mb-1">Security</h4>
                                <p class="text-xs text-gray-500">Enterprise grade</p>
                            </div>
                        </div>

                        <!-- Interactive Buy Section -->
                        <div class="mt-auto p-4 bg-white/5 rounded-[2.5rem] border border-white/10 backdrop-blur-xl flex items-center gap-4">
                            <button class="flex-1 py-5 bg-white text-black font-black rounded-[2rem] hover:bg-gray-200 transition-all transform active:scale-95 shadow-xl shadow-white/5">
                                Add to Cart
                            </button>
                            <button class="size-[4.5rem] bg-purple-600 text-white rounded-[2rem] flex items-center justify-center hover:bg-purple-500 transition-all shadow-xl shadow-purple-600/20">
                                <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Multimedia/Promotional Section -->
                <section class="mt-32 border-t border-white/5 pt-32 text-center">
                    <h2 class="text-3xl lg:text-5xl font-bold mb-8">Ultimate Experience</h2>
                    <div class="aspect-video w-full bg-gradient-to-br from-gray-900 to-[#121215] rounded-[3rem] border border-white/5 overflow-hidden flex items-center justify-center group relative">
                        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?auto=format&fit=crop&q=100&w=2000')] bg-cover bg-center opacity-20 group-hover:scale-105 transition-transform duration-1000 grayscale"></div>
                        <div class="relative z-10">
                            <button class="size-24 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center group-hover:scale-110 group-hover:bg-white transition-all text-white group-hover:text-black">
                                <svg class="size-10 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                            </button>
                            <p class="mt-6 font-bold tracking-widest text-sm uppercase">Watch Featurette</p>
                        </div>
                    </div>
                </section>
            </main>

            <!-- Footer -->
            <footer class="border-t border-white/5 py-12 bg-black/40 text-center">
                <p class="text-gray-500 text-sm font-light">
                    &copy; 2026 MULTIMEDIA PRO. Built with <span class="text-purple-500">Laravel & Vue</span>.
                </p>
            </footer>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;800&display=swap');

.font-sans {
    font-family: 'Outfit', sans-serif;
}

::-webkit-scrollbar {
    width: 10px;
}
::-webkit-scrollbar-track {
    background: #0a0a0c;
}
::-webkit-scrollbar-thumb {
    background: #1e1e24;
    border-radius: 5px;
}
::-webkit-scrollbar-thumb:hover {
    background: #2d2d35;
}
</style>
