<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Cashier Dashboard" />

    <div class="app-container Dashboard CashierDashboard">
        <div class="Hero">
            <h1>Welcome back {{ user.name }}</h1>
        </div>
    </div>
</template>