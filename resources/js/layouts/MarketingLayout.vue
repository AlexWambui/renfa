<script setup lang="ts">
import Footer from '@/components/custom/Footer.vue';
import Navbar from '@/components/custom/Navbar/Index.vue';
import { Toaster } from '@/components/ui/sonner';
</script>

<template>
    <Toaster />
    
    <Navbar />
    
    <main class="GuestLayout">
        <slot />
    </main>

    <Footer />
</template>
