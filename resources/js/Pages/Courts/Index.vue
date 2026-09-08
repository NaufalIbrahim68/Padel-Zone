<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({ courts: Array });

const formatPrice = (price) => 'Rp' + Number(price).toLocaleString('id-ID');
</script>

<template>
    <Head title="Courts" />
    <PublicLayout>
        <section class="py-16 bg-surface-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <p class="text-brand-600 font-semibold text-sm tracking-widest uppercase mb-2">Our Facilities</p>
                    <h1 class="section-title">Padel Courts</h1>
                    <p class="section-subtitle">Explore our premium courts and find the perfect one for your game.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div v-for="court in courts" :key="court.id" class="card hover:shadow-lg transition-all duration-300 group">
                        <div class="h-56 bg-surface-900 flex items-center justify-center relative overflow-hidden">
                            <img
                                v-if="court.image_url || court.image"
                                :src="court.image_url || ('/storage/' + court.image)"
                                :alt="court.name"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                @error="$event.target.src = '/images/' + court.image"
                            />
                            <svg v-else class="w-20 h-20 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <div class="absolute top-4 right-4">
                                <span class="text-xs font-semibold px-3 py-1 rounded-full" :class="court.type === 'Indoor' ? 'bg-blue-500 text-white' : 'bg-amber-500 text-white'">
                                    {{ court.type }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-surface-900 mb-2">{{ court.name }}</h2>
                            <p class="text-surface-500 text-sm mb-4 leading-relaxed">{{ court.description }}</p>
                            <div class="flex items-center justify-between pt-4 border-t border-surface-100">
                                <div>
                                    <p class="text-2xl font-bold text-brand-600">{{ formatPrice(court.price_per_hour) }}</p>
                                    <p class="text-xs text-surface-400">per hour</p>
                                </div>
                                <Link :href="route('booking')" class="btn-primary btn-sm">
                                    Book Now
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
