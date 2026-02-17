<script setup>
import { Head, Link } from '@inertiajs/vue3';

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
    
    <div class="min-h-screen bg-[#0a0a0c] text-white selection:bg-purple-600 selection:text-white font-sans">
        <!-- Modern Background Gradient -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-purple-900/20 blur-[120px] rounded-full"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-blue-900/20 blur-[120px] rounded-full"></div>
        </div>

        <div class="relative z-10">
            <!-- Navigation -->
            <nav class="flex items-center justify-between px-8 py-6 backdrop-blur-md bg-black/20 border-b border-white/5 sticky top-0 z-50">
                <div class="flex items-center gap-2">
                    <div class="size-10 bg-gradient-to-tr from-purple-600 to-blue-500 rounded-lg flex items-center justify-center shadow-lg shadow-purple-500/20">
                        <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 118 0m-4 5v2m-6 4h10M5 7h14a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white/90">MULTIMEDIA<span class="text-purple-500">PRO</span></span>
                </div>

                <div v-if="canLogin" class="flex items-center gap-6">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="text-sm font-medium text-white/70 hover:text-white transition-colors"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-sm font-medium text-white/70 hover:text-white transition-colors"
                        >
                            Log in
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="px-5 py-2.5 bg-white text-black text-sm font-bold rounded-full hover:bg-gray-200 transition-all transform active:scale-95 shadow-xl shadow-white/5"
                        >
                            Get Started
                        </Link>
                    </template>
                </div>
            </nav>

            <!-- Hero Section -->
            <header class="max-w-7xl mx-auto px-6 pt-24 pb-12 text-center">
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tighter mb-6 bg-clip-text text-transparent bg-gradient-to-b from-white to-gray-400">
                    Elevate Your Digital <br/> Experience
                </h1>
                <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed font-light">
                    Explore our curated collection of high-performance multimedia products designed for creators, thinkers, and innovators.
                </p>
                <div class="flex items-center justify-center gap-4">
                    <a href="#products" class="px-8 py-4 bg-purple-600 text-white font-bold rounded-full hover:bg-purple-500 transition-all shadow-2xl shadow-purple-600/20">
                        Explore Collection
                    </a>
                </div>
            </header>

            <!-- Product Grid -->
            <main id="products" class="max-w-7xl mx-auto px-6 py-20 pb-32">
                <div class="flex items-center justify-between mb-12">
                    <h2 class="text-3xl font-bold tracking-tight text-white/90">Featured Products</h2>
                    <div class="flex gap-2">
                        <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-xs text-white/60">20+ Items</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <div 
                        v-for="product in products" 
                        :key="product.id"
                        class="group relative bg-[#121215] border border-white/5 rounded-3xl overflow-hidden transition-all duration-500 hover:border-purple-500/50 hover:shadow-2xl hover:shadow-purple-500/10 hover:-translate-y-1 ring-1 ring-transparent hover:ring-purple-500/20"
                    >
                        <!-- Image Container (Placeholder for now) -->
                        <div class="aspect-[4/3] bg-gradient-to-br from-gray-800 to-gray-900 overflow-hidden relative">
                            <div class="absolute inset-0 flex items-center justify-center text-white/5 transform group-hover:scale-110 transition-transform duration-700">
                                <svg class="size-32" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                            </div>
                            <!-- Price Badge -->
                            <div class="absolute top-4 right-4 px-3 py-1.5 bg-black/60 backdrop-blur-md rounded-full border border-white/10 text-xs font-bold text-white shadow-xl">
                                {{ formatPrice(product.price) }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-white/90 mb-2 truncate group-hover:text-purple-400 transition-colors">
                                {{ product.title }}
                            </h3>
                            <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed mb-6 h-10 font-light">
                                {{ product.description }}
                            </p>
                            
                            <div class="flex items-center justify-between mt-auto">
                                <button class="w-full py-3 bg-white/5 text-white/80 text-sm font-bold rounded-2xl border border-white/10 hover:bg-white hover:text-black transition-all">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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

<style>
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
