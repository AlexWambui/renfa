<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import {PhoneCall, Mail} from "@lucide/vue";
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { Textarea } from '@/components/ui/textarea';
import contactMessageRoutes from '@/routes/contact-messages';

const form = useForm({
    name: '',
    phone_number: '',
    message: '',
});

const submitForm = () => {
    form.post(contactMessageRoutes.store.url(), {
        preserveScroll: true,
        onSuccess:() => {
            form.reset();
        }
    });
};
</script>

<template>
    <div class="ContactPage">
        <section class="Contact py-8">
            <div class="container-fluid grid lg:grid-cols-2 gap-8">
                <div class="content space-y-4">
                    <h1 class="font-bold text-heading-text lg:text-l-text">Let's Get In Touch</h1>
                    <p>Contact us using any of the following methods or fill out the form to request a callback</p>
                    <div class="phone space-y-2">
                        <p class="flex items-center gap-2">
                            <span class="bg-accent text-accent-foreground rounded-full p-2">
                                <PhoneCall class="w-4 h-4"/>
                            </span>
                            <span>+254 708 887 179</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="bg-accent text-accent-foreground rounded-full p-2">
                                <PhoneCall class="w-4 h-4"/>
                            </span>
                            <span>+254 115 706 106</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="bg-accent text-accent-foreground rounded-full p-2">
                                <PhoneCall class="w-4 h-4"/>
                            </span>
                            <span>+254 799 921 366</span>
                        </p>
                    </div>

                    <div class="email">
                        <p class="flex items-center gap-2">
                            <span class="bg-accent text-accent-foreground rounded-full p-2">
                                <Mail class="w-4 h-4"/>
                            </span>
                            <span>info@renfaconcreteltd.com</span>
                        </p>
                    </div>
                </div>

                <div class="form-wrapper">
                    <form @submit.prevent="submitForm">
                        <div class="inputs-group">
                            <Label for="name" class="required">Full Name</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Jane Doe"
                                autofocus
                            />
                            <InputError :message="form.errors.name" />
                        </div>

                        <div class="inputs-group">
                            <Label for="phone_number" class="required">Phone Number</Label>
                            <Input
                                id="phone"
                                v-model="form.phone_number"
                                type="text"
                                placeholder="+254 746 000 000"
                            />
                            <InputError :message="form.errors.phone_number" />
                        </div>

                        <div class="inputs-group">
                            <Label for="message">Message</Label>
                            <Textarea
                                id="message"
                                v-model="form.message"
                                rows="4"
                                placeholder="Enter your message..."
                            />
                            <InputError :message="form.errors.message" />
                        </div>

                        <div class="submit-buttons">
                            <Button type="submit" :disabled="form.processing">
                                <Spinner v-if="form.processing" />
                                Request a Callback
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</template>