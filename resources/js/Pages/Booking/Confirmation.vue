<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    booking: Object,
});

const formatPrice = (price) => 'Rp' + Number(price).toLocaleString('id-ID');
const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
};
const formatTime = (time) => time ? time.substring(0, 5) : '';
</script>

<template>
    <Head title="Booking Confirmation" />
    <PublicLayout>
        <section class="py-16 bg-surface-50 min-h-screen">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Success Icon -->
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-brand-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold font-display text-surface-900">Booking Confirmed!</h1>
                    <p class="text-surface-500 mt-2">Your court has been reserved successfully.</p>
                </div>

                <!-- Booking Details Card -->
                <div class="card p-8">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-surface-100">
                        <div>
                            <p class="text-xs text-surface-400 uppercase tracking-wider">Booking ID</p>
                            <p class="text-2xl font-bold text-surface-900 font-display">#{{ booking.id }}</p>
                        </div>
                        <StatusBadge :status="booking.status" />
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-surface-500">Customer</span>
                            <span class="text-sm font-medium text-surface-900">{{ booking.user.name }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-surface-500">Court</span>
                            <span class="text-sm font-medium text-surface-900">{{ booking.court.name }} ({{ booking.court.type }})</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-surface-500">Date</span>
                            <span class="text-sm font-medium text-surface-900">{{ formatDate(booking.booking_date) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-surface-500">Time</span>
                            <span class="text-sm font-medium text-surface-900">{{ formatTime(booking.start_time) }} - {{ formatTime(booking.end_time) }}</span>
                        </div>
                        <div v-if="booking.notes" class="flex justify-between items-start py-2">
                            <span class="text-sm text-surface-500">Notes</span>
                            <span class="text-sm text-surface-700 text-right max-w-[60%]">{{ booking.notes }}</span>
                        </div>

                        <div class="border-t border-surface-100 pt-4 mt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-base font-semibold text-surface-900">Total Price</span>
                                <span class="text-2xl font-bold text-brand-600">{{ formatPrice(booking.total_price) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <Link :href="route('my-bookings')" class="btn-primary flex-1 text-center">
                        View My Bookings
                    </Link>
                    <Link :href="route('booking')" class="btn-outline flex-1 text-center">
                        Book Another Court
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
