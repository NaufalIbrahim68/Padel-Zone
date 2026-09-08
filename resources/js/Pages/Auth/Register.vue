<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Create Account — PADELZONE" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-white font-display">Create Account</h1>
            <p class="text-sm text-surface-400 mt-1">Join PADELZONE and book your matches in seconds</p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <InputLabel for="name" value="Full Name" />
                <TextInput
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="e.g. Alex Johnson"
                />
                <InputError class="mt-1.5 text-red-400 text-xs" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email Address" />
                <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="alex@example.com"
                />
                <InputError class="mt-1.5 text-red-400 text-xs" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="phone" value="Phone Number (WhatsApp)" />
                <TextInput
                    id="phone"
                    type="tel"
                    v-model="form.phone"
                    placeholder="e.g. 081234567890"
                />
                <InputError class="mt-1.5 text-red-400 text-xs" :message="form.errors.phone" />
            </div>

            <div>
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    placeholder="At least 8 characters"
                />
                <InputError class="mt-1.5 text-red-400 text-xs" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Repeat password"
                />
                <InputError class="mt-1.5 text-red-400 text-xs" :message="form.errors.password_confirmation" />
            </div>

            <div class="pt-2">
                <PrimaryButton
                    class="w-full py-3 text-base font-bold"
                    :class="{ 'opacity-50': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Creating Account...</span>
                    <span v-else>Register</span>
                </PrimaryButton>
            </div>

            <div class="pt-4 text-center border-t border-surface-800">
                <p class="text-sm text-surface-400">
                    Already have an account?
                    <Link :href="route('login')" class="text-brand-400 hover:text-brand-300 font-semibold transition-colors">
                        Sign In
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>
