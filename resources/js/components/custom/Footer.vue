<script setup lang="ts">
import {PhoneCall} from "@lucide/vue";
import { computed } from 'vue';

const currentYear = computed(() => new Date().getFullYear());
</script>

<template>
    <footer>
        <div class="container-fluid px-4 lg:px-16 py-4 grid lg:grid-cols-2 gap-8">
            <div class="branding space-y-2">
                <p>Renfa Concrete LTD</p>
                <p>Plastic, ABS and steel moulds for pavers, balusters, ventilation blocks, cladding and cast-concrete garden pieces - plus the mixers, vibrators and oxide pigments to run a full production line. All held in stock in Nairobi, ready to move.</p>

                <div class="address mt-4">
                    <p>Maasai Road Highrise</p>
                    <p>Maasai Road, off Mombasa Road</p>
                </div>
            </div>

            <div class="contact space-y-2">
                <p>Contact Us</p>
                <p class="flex items-center gap-1"><PhoneCall class="w-4 h-4"/> <span>+254 708 887 179</span></p>
                <p class="flex items-center gap-1"><PhoneCall class="w-4 h-4"/> <span>+254 115 706 106</span></p>
                <p class="flex items-center gap-1"><PhoneCall class="w-4 h-4"/> <span>+254 799 921 366</span></p>
            </div>
        </div>

        <div class="copyrights grid gap-1 lg:flex lg:justify-between border-t border-border pt-4 px-4 lg:px-16 py-4">
            <p class="text">&copy; {{  currentYear  }}. Renfa. All rights reserved.</p>

            <div class="documents space-x-2">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </footer>
</template>