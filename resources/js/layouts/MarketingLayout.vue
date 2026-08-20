<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';
import Footer from '@/components/custom/Footer.vue';
import Navbar from '@/components/custom/Navbar/Index.vue';
import { Toaster } from '@/components/ui/sonner';
import { useAppearance } from '@/composables/useAppearance';

const { appearance, updateAppearance } = useAppearance();

// Store original theme to restore later
const originalTheme = ref<'light' | 'dark' | 'system'>('light');

// Force light theme on mount
onMounted(() => {
    // Store the current theme before changing
    originalTheme.value = appearance.value;
    
    // Force light theme
    updateAppearance('light');
});

// Restore original theme when leaving this layout
onUnmounted(() => {
    // Only restore if we had a theme stored
    if (originalTheme.value) {
        updateAppearance(originalTheme.value);
    }
});
</script>

<template>
    <Toaster />
    
    <Navbar />
    
    <main class="GuestLayout">
        <slot />
    </main>

    <Footer />
</template>