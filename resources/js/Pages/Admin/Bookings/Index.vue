<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import Pagination from '@/Components/Pagination.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    bookings: Object,
    courts: Array,
    filters: Object,
});

const formatPrice = (price) => 'Rp' + Number(price).toLocaleString('id-ID');
const formatDate = (dateStr) => new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
const formatTime = (time) => time ? time.substring(0, 5) : '';

const search = ref(props.filters?.search || '');
const filterDate = ref(props.filters?.date || '');
const filterCourt = ref(props.filters?.court_id || '');
const filterStatus = ref(props.filters?.status || '');

let searchTimeout = null;
const applyFilters = () => {
    router.get(route('admin.bookings.index'), {
        search: search.value || undefined,
        date: filterDate.value || undefined,
        court_id: filterCourt.value || undefined,
        status: filterStatus.value || undefined,
    }, { preserveState: true, replace: true });
};

watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 400);
});
watch([filterDate, filterCourt, filterStatus], applyFilters);

const statusAction = ref(null);
const actionBooking = ref(null);
const showModal = ref(false);

const openAction = (booking, status) => {
    actionBooking.value = booking;
    statusAction.value = status;
    showModal.value = true;
};

const confirmAction = () => {
    router.patch(route('admin.bookings.update-status', actionBooking.value.id), {
        status: statusAction.value,
    }, {
        onFinish: () => {
            showModal.value = false;
            actionBooking.value = null;
        },
    });
};

const statusLabels = { confirmed: 'Confirm', cancelled: 'Cancel', completed: 'Complete' };
</script>

<template>
    <Head title="Manage Bookings" />
    <AdminLayout>
        <template #header>
            <h1 class="text-xl font-bold text-surface-900">Bookings</h1>
        </template>

        <!-- Filters -->
        <div class="card p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <input v-model="search" type="text" class="input-field" placeholder="Search customer...">
                <input v-model="filterDate" type="date" class="input-field">
                <select v-model="filterCourt" class="input-field">
                    <option value="">All Courts</option>
                    <option v-for="court in courts" :key="court.id" :value="court.id">{{ court.name }}</option>
                </select>
                <select v-model="filterStatus" class="input-field">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-surface-50 border-b border-surface-200">
                        <tr>
                            <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-4 py-3">Customer</th>
                            <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-4 py-3 hidden lg:table-cell">Phone</th>
                            <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-4 py-3">Court</th>
                            <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-4 py-3">Date</th>
                            <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-4 py-3">Time</th>
                            <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-4 py-3 hidden md:table-cell">Price</th>
                            <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-4 py-3">Status</th>
                            <th class="text-right text-xs font-semibold text-surface-500 uppercase tracking-wider px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        <tr v-for="booking in bookings.data" :key="booking.id" class="hover:bg-surface-50 transition-colors">
                            <td class="px-4 py-3 text-sm font-medium text-surface-900">
                                {{ booking.user.name }}
                            </td>
                            <td class="px-4 py-3 text-sm text-surface-500 hidden lg:table-cell">{{ booking.user.phone || '—' }}</td>
                            <td class="px-4 py-3 text-sm text-surface-700">{{ booking.court.name }}</td>
                            <td class="px-4 py-3 text-sm text-surface-700">{{ formatDate(booking.booking_date) }}</td>
                            <td class="px-4 py-3 text-sm text-surface-700">{{ formatTime(booking.start_time) }}-{{ formatTime(booking.end_time) }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-surface-900 hidden md:table-cell">{{ formatPrice(booking.total_price) }}</td>
                            <td class="px-4 py-3"><StatusBadge :status="booking.status" /></td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <Link :href="route('admin.bookings.show', booking.id)" class="text-xs text-brand-600 hover:text-brand-700 font-medium px-2 py-1">View</Link>
                                    <button v-if="booking.status === 'pending'" @click="openAction(booking, 'confirmed')" class="text-xs text-blue-600 hover:text-blue-700 font-medium px-2 py-1">Confirm</button>
                                    <button v-if="booking.status === 'pending' || booking.status === 'confirmed'" @click="openAction(booking, 'cancelled')" class="text-xs text-red-600 hover:text-red-700 font-medium px-2 py-1">Cancel</button>
                                    <button v-if="booking.status === 'confirmed'" @click="openAction(booking, 'completed')" class="text-xs text-green-600 hover:text-green-700 font-medium px-2 py-1">Complete</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            <Pagination :links="bookings" />
        </div>

        <ConfirmationModal
            :show="showModal"
            :title="statusLabels[statusAction] + ' Booking'"
            :message="`Are you sure you want to ${statusLabels[statusAction]?.toLowerCase()} booking #${actionBooking?.id}?`"
            :confirm-text="statusLabels[statusAction]"
            :variant="statusAction === 'cancelled' ? 'danger' : 'primary'"
            @confirm="confirmAction"
            @cancel="showModal = false"
        />
    </AdminLayout>
</template>
