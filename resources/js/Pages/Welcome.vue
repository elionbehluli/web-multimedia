<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PixiBackground from '@/Components/PixiBackground.vue';
import PixiProductImage from '@/Components/PixiProductImage.vue';

defineProps({
    products: {
        type: Array,
        required: true,
    },
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};
</script>

<template>
    <Head title="Premium Product Showcase" />
    
    <div class="min-h-screen bg-white text-gray-900 selection:bg-emerald-500 selection:text-white font-sans">
        <!-- PixiJS Animated Background -->
        <PixiBackground />

        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-emerald-100/50 blur-[120px] rounded-full"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-teal-100/50 blur-[120px] rounded-full"></div>
        </div>

        <div class="relative z-10">
            <!-- Navigation -->
            <nav class="flex items-center justify-between px-8 py-6 backdrop-blur-md bg-white/80 border-b border-gray-100 sticky top-0 z-50">
                <div class="flex items-center gap-2">
                    <div class="size-10 bg-gradient-to-tr from-emerald-500 to-teal-400 rounded-lg flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 118 0m-4 5v2m-6 4h10M5 7h14a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-gray-900">MULTIMEDIA<span class="text-emerald-500">PRO</span></span>
                </div>

                <div v-if="canLogin" class="flex items-center gap-6">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors"
                        >
                            Log in
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="px-5 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-full hover:bg-black transition-all transform active:scale-95 shadow-xl shadow-gray-200"
                        >
                            Get Started
                        </Link>
                    </template>
                </div>
            </nav>

            <!-- Hero Section -->
            <header class="max-w-7xl mx-auto px-6 pt-24 pb-12 text-center">
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tighter mb-6 bg-clip-text text-transparent bg-gradient-to-b from-gray-900 to-gray-600">
                    Elevate Your Digital <br/> Experience
                </h1>
                <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed font-light">
                    Explore our curated collection of high-performance multimedia products designed for creators, thinkers, and innovators.
                </p>
                <div class="flex items-center justify-center gap-4">
                    <a href="#products" class="px-8 py-4 bg-emerald-600 text-white font-bold rounded-full hover:bg-emerald-500 transition-all shadow-2xl shadow-emerald-600/20">
                        Explore Collection
                    </a>
                </div>
            </header>

            <!-- Product Grid -->
            <main id="products" class="max-w-7xl mx-auto px-6 py-20 pb-32">
                <div class="flex items-center justify-between mb-12">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900">Featured Products</h2>
                    <div class="flex gap-2">
                        <span class="px-3 py-1 bg-gray-100 border border-gray-200 rounded-full text-xs text-gray-500">20+ Items</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <div 
                        v-for="product in products" 
                        :key="product.id"
                        class="group relative bg-white border border-gray-100 rounded-3xl overflow-hidden transition-all duration-500 hover:border-emerald-500/30 hover:shadow-2xl hover:shadow-emerald-500/10 hover:-translate-y-1 ring-1 ring-transparent hover:ring-emerald-500/5"
                    >
                        <!-- Image Container -->
                        <div class="aspect-[4/3] bg-gradient-to-br from-gray-800 to-gray-900 overflow-hidden relative">
                            <template v-if="product.images && product.images.length > 0">
                                <PixiProductImage 
                                    :src="`/${product.images[0].file_path}`" 
                                    :alt="product.title"
                                />
                            </template>
                            <div v-else class="absolute inset-0 flex items-center justify-center text-white/5">
                                <svg class="size-32" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                            </div>
                            <!-- Price Badge -->
                            <div class="absolute top-4 right-4 px-3 py-1.5 bg-white/90 backdrop-blur-md rounded-full border border-gray-100 text-xs font-bold text-gray-900 shadow-xl z-10 pointer-events-none">
                                {{ formatPrice(product.price) }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 truncate group-hover:text-emerald-600 transition-colors">
                                {{ product.title }}
                            </h3>
                            <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed mb-6 h-10 font-light">
                                {{ product.description }}
                            </p>
                            
                            <div class="flex items-center justify-between mt-auto">
                                <Link 
                                    :href="route('products.show', product.id)"
                                    class="w-full py-3 bg-gray-50 text-gray-700 text-sm font-bold rounded-2xl border border-gray-100 hover:bg-gray-900 hover:text-white transition-all text-center"
                                >
                                    View Details
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="border-t border-gray-100 py-12 bg-gray-50 text-center">
                <p class="text-gray-500 text-sm font-light">
                    &copy; 2026 MULTIMEDIA PRO. Built with <span class="text-emerald-500">Laravel & Vue</span>.
                </p>
            </footer>
        </div>
    </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;800&display=swap');

.font-sans {
    font-family: 'Outfit', sans-serif;
}

::-webkit-scrollbar {
    width: 10px;
}
::-webkit-scrollbar-track {
    background: #ffffff;
}
::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 5px;
}
::-webkit-scrollbar-thumb:hover {
    background: #d1d5db;
}
</style>
