<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({ court: Object });

const formatPrice = (price) => 'Rp' + Number(price).toLocaleString('id-ID');
</script>

<template>
    <Head :title="court.name" />
    <PublicLayout>
        <section class="py-16 bg-surface-50 min-h-screen">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <Link :href="route('courts.index')" class="inline-flex items-center text-sm text-surface-500 hover:text-brand-600 mb-6 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Courts
                </Link>

                <div class="card">
                    <div class="h-80 bg-surface-900 flex items-center justify-center relative overflow-hidden rounded-t-xl">
                        <img
                            v-if="court.image_url || court.image"
                            :src="court.image_url || ('/storage/' + court.image)"
                            :alt="court.name"
                            class="w-full h-full object-cover"
                            @error="$event.target.src = '/images/' + court.image"
                        />
                        <svg v-else class="w-24 h-24 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                        </svg>
                    </div>
                    <div class="p-8">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h1 class="text-3xl font-bold text-surface-900 font-display">{{ court.name }}</h1>
                                <span class="inline-block mt-2 text-xs font-semibold px-3 py-1 rounded-full" :class="court.type === 'Indoor' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700'">
                                    {{ court.type }}
                                </span>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-bold text-brand-600">{{ formatPrice(court.price_per_hour) }}</p>
                                <p class="text-sm text-surface-400">per hour</p>
                            </div>
                        </div>
                        <p class="text-surface-600 leading-relaxed mb-8">{{ court.description }}</p>
                        <Link :href="route('booking')" class="btn-primary text-lg px-8 py-3">
                            Book This Court
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
