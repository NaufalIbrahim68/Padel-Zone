<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12 bg-surface-950 text-surface-100 relative overflow-hidden selection:bg-brand-500 selection:text-white">
        <!-- Ambient background glows -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-brand-500/10 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 right-10 w-[400px] h-[300px] bg-brand-600/5 blur-[100px] rounded-full pointer-events-none"></div>

        <!-- Back to Home link -->
        <div class="w-full max-w-md mb-6 flex items-center justify-between z-10">
            <Link
                :href="route('home')"
                class="inline-flex items-center text-sm font-medium text-surface-400 hover:text-brand-400 transition-colors"
            >
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Home
            </Link>

            <span class="text-xs text-surface-500">Jakarta Venue</span>
        </div>

        <!-- Brand Logo Header -->
        <div class="mb-8 z-10">
            <Link :href="route('home')" class="block">
                <ApplicationLogo />
            </Link>
        </div>

        <!-- Main Card Container -->
        <div class="w-full max-w-md bg-surface-900/90 border border-surface-800/80 shadow-2xl shadow-black/60 rounded-3xl p-8 backdrop-blur-xl relative z-10">
            <slot />
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-xs text-surface-500 z-10">
            &copy; {{ new Date().getFullYear() }} PADELZONE. All rights reserved.
        </div>
    </div>
</template>
