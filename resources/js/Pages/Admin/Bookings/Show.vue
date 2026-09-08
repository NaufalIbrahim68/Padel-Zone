<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({ booking: Object });

const formatPrice = (price) => 'Rp' + Number(price).toLocaleString('id-ID');
const formatDate = (dateStr) => new Date(dateStr).toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
const formatTime = (time) => time ? time.substring(0, 5) : '';

const updateStatus = (status) => {
    router.patch(route('admin.bookings.update-status', props.booking.id), { status });
};
</script>

<template>
    <Head :title="'Booking #' + booking.id" />
    <AdminLayout>
        <template #header>
            <div class="flex items-center space-x-2">
                <Link :href="route('admin.bookings.index')" class="text-surface-400 hover:text-surface-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </Link>
                <h1 class="text-xl font-bold text-surface-900">Booking #{{ booking.id }}</h1>
            </div>
        </template>

        <div class="max-w-3xl">
            <div class="card p-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-surface-100">
                    <div>
                        <p class="text-xs text-surface-400 uppercase tracking-wider">Booking ID</p>
                        <p class="text-2xl font-bold text-surface-900 font-display">#{{ booking.id }}</p>
                    </div>
                    <StatusBadge :status="booking.status" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm font-semibold text-surface-500 uppercase tracking-wider mb-3">Customer</h3>
                        <p class="text-surface-900 font-medium">{{ booking.user.name }}</p>
                        <p class="text-sm text-surface-500">{{ booking.user.email }}</p>
                        <p class="text-sm text-surface-500">{{ booking.user.phone || 'No phone' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-surface-500 uppercase tracking-wider mb-3">Booking Details</h3>
                        <p class="text-surface-900 font-medium">{{ booking.court.name }} ({{ booking.court.type }})</p>
                        <p class="text-sm text-surface-500">{{ formatDate(booking.booking_date) }}</p>
                        <p class="text-sm text-surface-500">{{ formatTime(booking.start_time) }} - {{ formatTime(booking.end_time) }}</p>
                    </div>
                </div>

                <div v-if="booking.notes" class="mb-6 p-3 bg-surface-50 rounded-lg">
                    <p class="text-xs font-semibold text-surface-500 uppercase mb-1">Notes</p>
                    <p class="text-sm text-surface-700">{{ booking.notes }}</p>
                </div>

                <div class="border-t border-surface-100 pt-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-base font-semibold text-surface-900">Total Price</span>
                        <span class="text-2xl font-bold text-brand-600">{{ formatPrice(booking.total_price) }}</span>
                    </div>
                </div>

                <!-- Admin Actions -->
                <div class="flex flex-wrap gap-2">
                    <button v-if="booking.status === 'pending'" @click="updateStatus('confirmed')" class="btn-primary btn-sm">Confirm</button>
                    <button v-if="booking.status === 'confirmed'" @click="updateStatus('completed')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 text-sm transition-colors">Complete</button>
                    <button v-if="booking.status === 'pending' || booking.status === 'confirmed'" @click="updateStatus('cancelled')" class="btn-danger btn-sm">Cancel</button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
