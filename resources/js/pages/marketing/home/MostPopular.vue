<script setup lang="ts">
import type {Product} from '@/types/product';

defineProps<{
    most_popular: Product[];
}>();
</script>

<template>
    <section class="MostPopularProducts">
        <div class="container-fluid">
            <div class="header mb-4 flex lg:items-center justify-between">
                <h2 class="text-heading-text lg:text-l-text font-bold uppercase">Most Popular</h2>
                <a href="/catalogue" class="font-medium">View all</a>
            </div>

            <div class="products-wrapper grid gap-8 lg:grid-cols-4">
                <div class="product" v-for="product in most_popular" :key="product.id">
                    <div class="image h-50 w-full">
                        <img :src="product.thumbnail_url" :alt="product.name" class="w-full h-full object-cover" />
                    </div>

                    <div class="content p-2 space-y-2">
                        <p class="capitalize font-bold">{{ product.name }}</p>
                        <p class="font-bold">Ksh. {{ product.price }}</p>
                        <p>{{ product.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
