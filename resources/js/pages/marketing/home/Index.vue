<script setup lang="ts">
import Hero from './Hero.vue';
import MostPopular from './MostPopular.vue';

defineProps<{
    new_arrivals: any[];
    most_popular: any[];
}>();
</script>

<template>
    <div class="HomePage">
        <Hero />

        <MostPopular :most_popular="most_popular" />
    </div>
</template>

<style scoped>
section {
    margin: 0 0 60px 0;
}
</style>