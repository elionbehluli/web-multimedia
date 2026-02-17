<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    products: {
        type: Array,
        required: true,
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
    <Head title="Admin Dashboard - Products" />

    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                <Link
                    href="/"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                >
                    &larr; Back to Shop
                </Link>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Product List
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Manage all products currently in the database.
                    </p>
                </div>
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                ID
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Title
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Description
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Price
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr 
                                            v-for="product in products" 
                                            :key="product.id" 
                                            @click="$inertia.visit(route('admin.products.show', product.id))"
                                            class="cursor-pointer hover:bg-gray-50 transition-colors"
                                        >
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                #{{ product.id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 uppercase">
                                                {{ product.title }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                                {{ product.description }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                                {{ formatPrice(product.price) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</button>
                                                <button class="text-red-600 hover:text-red-900">Delete</button>
                                            </td>
                                        </tr>
                                        <tr v-if="products.length === 0">
                                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                                                No products found in the database.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
