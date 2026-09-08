<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const auth = computed(() => usePage().props.auth);
</script>

<template>
    <Head title="Dashboard" />
    <PublicLayout>
        <section class="py-12 bg-surface-50 min-h-screen">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold font-display text-surface-900">Welcome back, {{ auth.user.name }}!</h1>
                    <p class="text-surface-500 mt-1">Manage your bookings and profile from here.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <Link :href="route('booking')" class="card p-6 hover:shadow-md transition-shadow group">
                        <div class="w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-brand-100 transition-colors">
                            <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                        <h3 class="font-semibold text-surface-900 mb-1">Book a Court</h3>
                        <p class="text-sm text-surface-500">Reserve your next padel session.</p>
                    </Link>

                    <Link :href="route('my-bookings')" class="card p-6 hover:shadow-md transition-shadow group">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-100 transition-colors">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <h3 class="font-semibold text-surface-900 mb-1">My Bookings</h3>
                        <p class="text-sm text-surface-500">View your booking history.</p>
                    </Link>

                    <Link :href="route('courts.index')" class="card p-6 hover:shadow-md transition-shadow group">
                        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-100 transition-colors">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                        </div>
                        <h3 class="font-semibold text-surface-900 mb-1">View Courts</h3>
                        <p class="text-sm text-surface-500">Explore our available courts.</p>
                    </Link>

                    <Link :href="route('profile.edit')" class="card p-6 hover:shadow-md transition-shadow group">
                        <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-yellow-100 transition-colors">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h3 class="font-semibold text-surface-900 mb-1">Profile</h3>
                        <p class="text-sm text-surface-500">Update your personal information.</p>
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
