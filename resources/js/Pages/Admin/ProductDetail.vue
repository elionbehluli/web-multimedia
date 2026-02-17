<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const fileInput = ref(null);
const form = useForm({
    image: null,
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};

const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        uploadImage();
    }
};

const uploadImage = () => {
    form.post(route('admin.products.images.store', props.product.id), {
        onSuccess: () => {
            form.reset();
        },
        forceFormData: true,
    });
};
</script>

<template>
    <Head :title="`Admin - ${product.title}`" />

    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <nav class="mb-8">
                <Link
                    :href="route('admin.products.index')"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center gap-2"
                >
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Product List
                </Link>
            </nav>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 uppercase">{{ product.title }}</h1>
                        <p class="mt-1 text-sm text-gray-500">Product ID: #{{ product.id }}</p>
                    </div>
                    <div class="text-2xl font-bold text-indigo-600">
                        {{ formatPrice(product.price) }}
                    </div>
                </div>
                
                <div class="px-4 py-8 sm:px-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                    <p class="text-gray-600 leading-relaxed mb-10">
                        {{ product.description || 'No description provided.' }}
                    </p>

                    <h3 class="text-lg font-medium text-gray-900 mb-6 font-bold">Product Photos</h3>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <!-- Existing Photos -->
                        <div 
                            v-for="image in product.images" 
                            :key="image.id"
                            class="aspect-square bg-gray-100 rounded-xl overflow-hidden border border-gray-200 relative group"
                        >
                            <img :src="`/${image.file_path}`" class="w-full h-full object-cover" />
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <button class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Add Photo Button -->
                        <button 
                            @click="triggerFileInput"
                            :disabled="form.processing"
                            class="aspect-square bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl flex flex-col items-center justify-center text-gray-400 hover:border-indigo-500 hover:text-indigo-500 hover:bg-indigo-50 transition-all group relative disabled:opacity-50"
                        >
                            <input 
                                type="file" 
                                ref="fileInput" 
                                @change="handleFileChange" 
                                class="hidden" 
                                accept="image/*"
                            />
                            
                            <template v-if="form.processing">
                                <svg class="animate-spin h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            <template v-else>
                                <svg class="size-8 mb-1 transform group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="text-xs font-bold uppercase tracking-wider">Add Photo</span>
                            </template>
                        </button>
                    </div>
                    
                    <div v-if="form.errors.image" class="mt-2 text-sm text-red-600">
                        {{ form.errors.image }}
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-end gap-3 border-t border-gray-200">
                    <button class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                        Archive Product
                    </button>
                    <button class="px-6 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 transition-all">
                        Edit Information
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
