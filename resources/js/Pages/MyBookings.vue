<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import Pagination from '@/Components/Pagination.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    bookings: Object,
});

const formatPrice = (price) => 'Rp' + Number(price).toLocaleString('id-ID');
const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};
const formatTime = (time) => time ? time.substring(0, 5) : '';

const cancellingBooking = ref(null);
const showCancelModal = ref(false);

const openCancelModal = (booking) => {
    cancellingBooking.value = booking;
    showCancelModal.value = true;
};

const confirmCancel = () => {
    router.patch(route('my-bookings.cancel', cancellingBooking.value.id), {}, {
        onFinish: () => {
            showCancelModal.value = false;
            cancellingBooking.value = null;
        },
    });
};
</script>

<template>
    <Head title="My Bookings" />
    <PublicLayout>
        <section class="py-12 bg-surface-50 min-h-screen">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-2xl font-bold font-display text-surface-900">My Bookings</h1>
                        <p class="text-sm text-surface-500 mt-1">View and manage your court reservations.</p>
                    </div>
                    <Link :href="route('booking')" class="btn-primary btn-sm">
                        Book a Court
                    </Link>
                </div>

                <div v-if="bookings.data.length" class="space-y-4">
                    <!-- Desktop table -->
                    <div class="hidden md:block card overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-surface-50 border-b border-surface-200">
                                <tr>
                                    <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-6 py-3">ID</th>
                                    <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-6 py-3">Court</th>
                                    <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-6 py-3">Date</th>
                                    <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-6 py-3">Time</th>
                                    <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-6 py-3">Price</th>
                                    <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-6 py-3">Status</th>
                                    <th class="text-right text-xs font-semibold text-surface-500 uppercase tracking-wider px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-surface-100">
                                <tr v-for="booking in bookings.data" :key="booking.id" class="hover:bg-surface-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-surface-900">#{{ booking.id }}</td>
                                    <td class="px-6 py-4 text-sm text-surface-700">{{ booking.court.name }}</td>
                                    <td class="px-6 py-4 text-sm text-surface-700">{{ formatDate(booking.booking_date) }}</td>
                                    <td class="px-6 py-4 text-sm text-surface-700">{{ formatTime(booking.start_time) }} - {{ formatTime(booking.end_time) }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-surface-900">{{ formatPrice(booking.total_price) }}</td>
                                    <td class="px-6 py-4"><StatusBadge :status="booking.status" /></td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <Link :href="route('bookings.show', booking.id)" class="text-sm text-brand-600 hover:text-brand-700 font-medium">View</Link>
                                        <button
                                            v-if="booking.status === 'pending' || booking.status === 'confirmed'"
                                            @click="openCancelModal(booking)"
                                            class="text-sm text-red-600 hover:text-red-700 font-medium"
                                        >Cancel</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile cards -->
                    <div class="md:hidden space-y-3">
                        <div v-for="booking in bookings.data" :key="booking.id" class="card p-4">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-bold text-surface-900">#{{ booking.id }}</span>
                                <StatusBadge :status="booking.status" />
                            </div>
                            <div class="space-y-1 text-sm text-surface-600">
                                <p><span class="font-medium">Court:</span> {{ booking.court.name }}</p>
                                <p><span class="font-medium">Date:</span> {{ formatDate(booking.booking_date) }}</p>
                                <p><span class="font-medium">Time:</span> {{ formatTime(booking.start_time) }} - {{ formatTime(booking.end_time) }}</p>
                                <p><span class="font-medium">Price:</span> {{ formatPrice(booking.total_price) }}</p>
                            </div>
                            <div class="flex space-x-2 mt-3 pt-3 border-t border-surface-100">
                                <Link :href="route('bookings.show', booking.id)" class="btn-primary btn-sm flex-1 text-center text-xs">View</Link>
                                <button
                                    v-if="booking.status === 'pending' || booking.status === 'confirmed'"
                                    @click="openCancelModal(booking)"
                                    class="btn-danger btn-sm flex-1 text-xs"
                                >Cancel</button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <Pagination :links="bookings" />
                    </div>
                </div>

                <!-- Empty state -->
                <div v-else class="card p-12 text-center">
                    <svg class="w-16 h-16 text-surface-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-surface-900 mb-2">No bookings yet</h3>
                    <p class="text-surface-500 mb-6">You haven't made any court reservations.</p>
                    <Link :href="route('booking')" class="btn-primary">Book Your First Court</Link>
                </div>
            </div>
        </section>

        <ConfirmationModal
            :show="showCancelModal"
            title="Cancel Booking"
            :message="`Are you sure you want to cancel booking #${cancellingBooking?.id}? This action cannot be undone.`"
            confirm-text="Yes, Cancel Booking"
            @confirm="confirmCancel"
            @cancel="showCancelModal = false"
        />
    </PublicLayout>
</template>
