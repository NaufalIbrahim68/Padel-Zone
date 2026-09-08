<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password — PADELZONE" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-white font-display">Reset Password</h1>
            <p class="text-sm text-surface-400 mt-2">
                Enter your email address and we'll send you a password reset link to get back into your account.
            </p>
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-brand-400 p-3 bg-brand-500/10 border border-brand-500/20 rounded-xl">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <InputLabel for="email" value="Email Address" />
                <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                />
                <InputError class="mt-1.5 text-red-400 text-xs" :message="form.errors.email" />
            </div>

            <div class="pt-2">
                <PrimaryButton
                    class="w-full py-3 text-sm font-bold"
                    :class="{ 'opacity-50': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Sending Reset Link...</span>
                    <span v-else>Email Password Reset Link</span>
                </PrimaryButton>
            </div>

            <div class="pt-4 text-center border-t border-surface-800">
                <Link :href="route('login')" class="text-sm text-surface-400 hover:text-brand-400 font-medium transition-colors">
                    ← Back to Sign In
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
