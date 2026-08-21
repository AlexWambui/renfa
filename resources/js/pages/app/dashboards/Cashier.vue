<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

interface Props {
    stats: {
        total_products: number;
        total_product_categories: number;
    }
};

defineProps<Props>();
</script>

<template>
    <Head title="Cashier Dashboard" />

    <div class="Dashboard CashierDashboard space-y-12">
        <section class="header">
            <div class="flex items-center gap-4">
                <p>Hi {{ user.name }}</p>
                <span class="text-xs text-blue-900 bg-blue-100 py-1 px-2 rounded-sm">{{ user.role_label }}</span>
            </div>
        </section>

        <section class="stats-wrapper">
            <h2 class="mb-4 font-medium">Platform Statistics</h2>

            <div class="stats grid gap-8 lg:grid-cols-6">
                <div class="stat border border-border p-4 rounded-lg space-y-0.5">
                    <p class="text-[24px] font-bold">{{ stats.total_products }}</p>
                    <p>Products</p>
                    <div class="extras">
                        <span class="text-sm text-muted-foreground">{{ stats.total_product_categories }} Categories</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>