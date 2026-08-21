<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Admin Dashboard" />

    <div class="Dashboard AdminDashboard">
        <h1>{{ user.email }}</h1>
        <p>{{ user.role_label }}</p>
        <p>{{ user.status_label }}</p>
    </div>
</template>