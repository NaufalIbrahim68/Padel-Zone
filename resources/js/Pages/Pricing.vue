<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({ courts: Array });

const formatPrice = (price) => 'Rp' + Number(price).toLocaleString('id-ID');
</script>

<template>
    <Head title="Pricing" />
    <PublicLayout>
        <section class="py-16 bg-surface-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <p class="text-brand-600 font-semibold text-sm tracking-widest uppercase mb-2">Pricing</p>
                    <h1 class="section-title">Simple, Transparent Pricing</h1>
                    <p class="section-subtitle">No hidden fees. Book per hour and pay only for what you play.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
                    <div v-for="court in courts" :key="court.id" class="card p-6 text-center hover:shadow-lg transition-shadow group">
                        <div class="w-14 h-14 mx-auto mb-4 rounded-full flex items-center justify-center" :class="court.type === 'Indoor' ? 'bg-blue-100' : 'bg-amber-100'">
                            <svg class="w-7 h-7" :class="court.type === 'Indoor' ? 'text-blue-600' : 'text-amber-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-surface-900 mb-1">{{ court.name }}</h3>
                        <p class="text-xs text-surface-400 mb-4">{{ court.type }}</p>
                        <p class="text-3xl font-bold text-surface-900 mb-1">{{ formatPrice(court.price_per_hour) }}</p>
                        <p class="text-sm text-surface-400 mb-6">per hour</p>
                        <ul class="text-sm text-surface-500 space-y-2 mb-6 text-left">
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-brand-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Professional-grade surface</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-brand-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>1-hour time slots</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-brand-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Online booking</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-brand-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Locker & shower access</span>
                            </li>
                        </ul>
                        <Link :href="route('booking')" class="btn-primary w-full btn-sm">Book Now</Link>
                    </div>
                </div>

                <div class="card p-8 text-center max-w-2xl mx-auto">
                    <h3 class="text-xl font-bold text-surface-900 mb-2">Operating Hours</h3>
                    <p class="text-surface-500 mb-4">Our courts are available every day</p>
                    <p class="text-3xl font-display font-bold text-brand-600">06:00 — 23:00</p>
                    <p class="text-sm text-surface-400 mt-2">Monday through Sunday</p>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
