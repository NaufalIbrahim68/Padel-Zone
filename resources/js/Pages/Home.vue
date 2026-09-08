<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    courts: Array,
});

const formatPrice = (price) => {
    return 'Rp' + Number(price).toLocaleString('id-ID');
};

const features = [
    { icon: 'court', title: '4 Premium Courts', desc: 'Professional-grade indoor & outdoor courts' },
    { icon: 'clock', title: 'Open 06:00 - 23:00', desc: 'Flexible scheduling every day of the week' },
    { icon: 'gear', title: 'Pro Equipment', desc: 'Rackets and balls available for rent' },
    { icon: 'wifi', title: 'Full Amenities', desc: 'Locker rooms, showers, lounge & café' },
];

const steps = [
    { num: '01', title: 'Choose Date & Court', desc: 'Select your preferred date and browse available courts.' },
    { num: '02', title: 'Pick a Time Slot', desc: 'View real-time availability and select your slot.' },
    { num: '03', title: 'Confirm & Play', desc: 'Review your booking details, confirm, and you\'re all set!' },
];

const testimonials = [
    { name: 'Andi Wijaya', text: 'Best padel facility in the city. The courts are world-class and the booking system is incredibly easy to use.', role: 'Regular Player' },
    { name: 'Maya Sari', text: 'Love the atmosphere here! Great courts, friendly staff, and the online booking saves so much time.', role: 'Club Member' },
    { name: 'Dimas Prasetyo', text: 'Finally a proper padel venue with a modern booking system. No more phone calls — just pick and play!', role: 'Tournament Player' },
];
</script>

<template>
    <Head title="Home" />

    <PublicLayout>
        <!-- Hero Section -->
        <section class="relative bg-surface-900 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-surface-900 via-surface-900 to-brand-900/30"></div>
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-20 left-10 w-72 h-72 bg-brand-500 rounded-full filter blur-[100px]"></div>
                <div class="absolute bottom-20 right-10 w-96 h-96 bg-brand-600 rounded-full filter blur-[120px]"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-36">
                <div class="text-center">
                    <p class="text-brand-400 font-semibold text-sm tracking-widest uppercase mb-4">PREMIUM PADEL EXPERIENCE</p>
                    <h1 class="text-4xl sm:text-5xl md:text-7xl font-display font-black text-white tracking-tight leading-tight">
                        PLAY. COMPETE.<br>
                        <span class="text-brand-400">CONNECT.</span>
                    </h1>
                    <p class="mt-6 text-lg md:text-xl text-surface-400 max-w-2xl mx-auto leading-relaxed">
                        Experience world-class padel courts with seamless online booking.
                        Your game, your schedule — book in seconds.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                        <Link :href="route('booking')" class="btn-primary text-lg px-8 py-4">
                            Book a Court
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </Link>
                        <Link :href="route('courts.index')" class="btn-outline text-lg px-8 py-4 border-surface-600 text-surface-300 hover:bg-surface-800 hover:text-white hover:border-surface-600">
                            View Courts
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Scroll indicator -->
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
                <svg class="w-6 h-6 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </div>
        </section>

        <!-- About Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <p class="text-brand-600 font-semibold text-sm tracking-widest uppercase mb-2">About Us</p>
                    <h2 class="section-title">Jakarta's Premier Padel Club</h2>
                    <p class="section-subtitle">
                        PADELZONE brings the fastest-growing racket sport to life with world-class facilities and a passionate community.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="feature in features" :key="feature.title" class="card p-6 text-center hover:shadow-md transition-shadow">
                        <div class="w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <svg v-if="feature.icon === 'court'" class="w-7 h-7 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <svg v-if="feature.icon === 'clock'" class="w-7 h-7 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <svg v-if="feature.icon === 'gear'" class="w-7 h-7 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg v-if="feature.icon === 'wifi'" class="w-7 h-7 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                        </div>
                        <h3 class="font-semibold text-surface-900 mb-1">{{ feature.title }}</h3>
                        <p class="text-sm text-surface-500">{{ feature.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Courts Preview -->
        <section class="py-20 bg-surface-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <p class="text-brand-600 font-semibold text-sm tracking-widest uppercase mb-2">Our Courts</p>
                    <h2 class="section-title">World-Class Playing Surfaces</h2>
                    <p class="section-subtitle">Choose from our selection of premium indoor and outdoor padel courts.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="court in courts" :key="court.id" class="card hover:shadow-lg transition-all duration-300 group">
                        <div class="h-48 relative overflow-hidden bg-surface-900 flex items-center justify-center">
                            <img
                                v-if="court.image_url || court.image"
                                :src="court.image_url || ('/storage/' + court.image)"
                                :alt="court.name"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                @error="$event.target.src = '/images/' + court.image"
                            />
                            <div v-else class="h-full w-full bg-gradient-to-br from-brand-600 to-brand-800 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white/30 group-hover:text-white/50 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-surface-900">{{ court.name }}</h3>
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full" :class="court.type === 'Indoor' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700'">
                                    {{ court.type }}
                                </span>
                            </div>
                            <p class="text-sm text-surface-500 mb-3 line-clamp-2">{{ court.description }}</p>
                            <div class="flex items-center justify-between">
                                <p class="text-lg font-bold text-brand-600">{{ formatPrice(court.price_per_hour) }}<span class="text-xs font-normal text-surface-400">/hour</span></p>
                                <Link :href="route('booking')" class="text-sm text-brand-600 hover:text-brand-700 font-medium">
                                    Book →
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <p class="text-brand-600 font-semibold text-sm tracking-widest uppercase mb-2">How It Works</p>
                    <h2 class="section-title">Book in 3 Simple Steps</h2>
                    <p class="section-subtitle">Our seamless booking system gets you on the court in minutes.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div v-for="step in steps" :key="step.num" class="text-center">
                        <div class="w-16 h-16 bg-surface-900 rounded-2xl flex items-center justify-center mx-auto mb-5">
                            <span class="text-brand-400 font-display font-bold text-lg">{{ step.num }}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-surface-900 mb-2">{{ step.title }}</h3>
                        <p class="text-sm text-surface-500 leading-relaxed">{{ step.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-20 bg-surface-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <p class="text-brand-400 font-semibold text-sm tracking-widest uppercase mb-2">Testimonials</p>
                    <h2 class="text-3xl md:text-4xl font-bold font-display text-white">What Our Players Say</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="t in testimonials" :key="t.name" class="bg-surface-800 rounded-xl p-6 border border-surface-700">
                        <div class="flex items-center space-x-1 mb-4">
                            <svg v-for="i in 5" :key="i" class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <p class="text-surface-300 text-sm leading-relaxed mb-4">"{{ t.text }}"</p>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-brand-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                {{ t.name.charAt(0) }}
                            </div>
                            <div>
                                <p class="text-white font-medium text-sm">{{ t.name }}</p>
                                <p class="text-surface-500 text-xs">{{ t.role }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-20 bg-brand-600">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-4">Ready to Play?</h2>
                <p class="text-brand-100 text-lg mb-8">Book your court now and experience padel at its finest.</p>
                <Link :href="route('booking')" class="inline-flex items-center px-8 py-4 bg-white text-brand-700 font-bold rounded-lg hover:bg-brand-50 transition-colors text-lg">
                    Book a Court Now
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </Link>
            </div>
        </section>
    </PublicLayout>
</template>
