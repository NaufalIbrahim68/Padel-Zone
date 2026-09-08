<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const setDemoCredentials = (role) => {
    if (role === 'admin') {
        form.email = 'admin@padelzone.com';
        form.password = 'password';
    } else {
        form.email = 'user@padelzone.com';
        form.password = 'password';
    }
};

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in — PADELZONE" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-white font-display">Welcome Back</h1>
            <p class="text-sm text-surface-400 mt-1">Sign in to book courts and manage your schedule</p>
        </div>

        <!-- Quick Demo Fill Pills -->
        <div class="mb-6 p-3 rounded-2xl bg-surface-950/60 border border-surface-800">
            <p class="text-xs text-surface-400 font-medium mb-2 flex items-center justify-between">
                <span>Quick Demo Login:</span>
                <span class="text-[11px] text-brand-400">Click to autofill</span>
            </p>
            <div class="grid grid-cols-2 gap-2">
                <button
                    type="button"
                    @click="setDemoCredentials('admin')"
                    class="px-2.5 py-1.5 text-xs font-semibold rounded-xl bg-surface-800 hover:bg-surface-700 text-brand-400 border border-brand-500/20 hover:border-brand-500/50 transition-all text-center"
                >
                    Admin Demo
                </button>
                <button
                    type="button"
                    @click="setDemoCredentials('user')"
                    class="px-2.5 py-1.5 text-xs font-semibold rounded-xl bg-surface-800 hover:bg-surface-700 text-surface-200 border border-surface-700 hover:border-surface-600 transition-all text-center"
                >
                    Customer Demo
                </button>
            </div>
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

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <InputLabel for="password" value="Password" class="mb-0" />
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs text-brand-400 hover:text-brand-300 font-medium transition-colors"
                    >
                        Forgot password?
                    </Link>
                </div>
                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <InputError class="mt-1.5 text-red-400 text-xs" :message="form.errors.password" />
            </div>

            <div class="flex items-center pt-1">
                <label class="flex items-center cursor-pointer select-none">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-xs text-surface-300">Keep me signed in</span>
                </label>
            </div>

            <div class="pt-2">
                <PrimaryButton
                    class="w-full py-3 text-base font-bold"
                    :class="{ 'opacity-50': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Signing in...</span>
                    <span v-else>Sign In</span>
                </PrimaryButton>
            </div>

            <div class="pt-4 text-center border-t border-surface-800">
                <p class="text-sm text-surface-400">
                    Don't have an account?
                    <Link :href="route('register')" class="text-brand-400 hover:text-brand-300 font-semibold transition-colors">
                        Create an account
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>
