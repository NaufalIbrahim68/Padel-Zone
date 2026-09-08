<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import BookingTrendChart from '@/Components/Admin/BookingTrendChart.vue';
import PeakBookingHoursChart from '@/Components/Admin/PeakBookingHoursChart.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,
    peakBookingHours: {
        type: Array,
        default: () => [],
    },
    bookingTrend: {
        type: Array,
        default: () => [],
    },
    peakPeriod: {
        type: String,
        default: '7d',
    },
    trendPeriod: {
        type: String,
        default: '7d',
    },
});

const formatPrice = (price) => 'Rp' + Number(price).toLocaleString('id-ID');
const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
};
const formatTime = (time) => (time ? time.substring(0, 5) : '');
</script>

<template>
    <Head title="Admin Dashboard" />
    <AdminLayout>
        <template #header>
            <h1 class="text-xl font-bold text-surface-900">Dashboard</h1>
        </template>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <StatCard title="Today's Bookings" :value="stats.todaysBookings" icon="calendar" color="brand" />
            <StatCard title="Today's Revenue" :value="formatPrice(stats.todaysRevenue)" icon="money" color="blue" />
            <StatCard title="Available Courts" :value="stats.availableCourts + '/' + stats.totalCourts" icon="court" color="yellow" />
            <StatCard title="Upcoming Bookings" :value="stats.upcomingBookings" icon="clock" color="purple" />
        </div>

        <!-- Charts Section (Booking Trend & Peak Booking Hours) -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
            <div>
                <BookingTrendChart :data="bookingTrend" :period="trendPeriod" />
            </div>
            <div>
                <PeakBookingHoursChart :data="peakBookingHours" :period="peakPeriod" />
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="card">
            <div class="px-6 py-4 border-b border-surface-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-surface-900">Recent Bookings</h2>
                <Link :href="route('admin.bookings.index')" class="text-sm text-brand-600 hover:text-brand-700 font-medium">View All →</Link>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-surface-50">
                        <tr>
                            <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-6 py-3">ID</th>
                            <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-6 py-3">Customer</th>
                            <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-6 py-3">Court</th>
                            <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-6 py-3">Date</th>
                            <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-6 py-3">Time</th>
                            <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        <tr v-for="booking in stats.recentBookings" :key="booking.id" class="hover:bg-surface-50 transition-colors">
                            <td class="px-6 py-3 text-sm font-medium text-surface-900">#{{ booking.id }}</td>
                            <td class="px-6 py-3 text-sm text-surface-700">{{ booking.user?.name }}</td>
                            <td class="px-6 py-3 text-sm text-surface-700">{{ booking.court?.name }}</td>
                            <td class="px-6 py-3 text-sm text-surface-700">{{ formatDate(booking.booking_date) }}</td>
                            <td class="px-6 py-3 text-sm text-surface-700">{{ formatTime(booking.start_time) }} - {{ formatTime(booking.end_time) }}</td>
                            <td class="px-6 py-3"><StatusBadge :status="booking.status" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
