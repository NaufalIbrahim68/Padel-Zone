<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    date: String,
    courts: Array,
    schedule: Array,
});

const selectedDate = ref(props.date);

watch(selectedDate, (val) => {
    router.get(route('admin.calendar'), { date: val }, { preserveState: true, replace: true });
});

const formatDateDisplay = (dateStr) => {
    const date = new Date(dateStr + 'T00:00:00');
    return date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
};

const goToday = () => {
    const today = new Date();
    const y = today.getFullYear();
    const m = String(today.getMonth() + 1).padStart(2, '0');
    const d = String(today.getDate()).padStart(2, '0');
    selectedDate.value = `${y}-${m}-${d}`;
};

const prevDay = () => {
    const date = new Date(selectedDate.value + 'T00:00:00');
    date.setDate(date.getDate() - 1);
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    selectedDate.value = `${y}-${m}-${d}`;
};

const nextDay = () => {
    const date = new Date(selectedDate.value + 'T00:00:00');
    date.setDate(date.getDate() + 1);
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    selectedDate.value = `${y}-${m}-${d}`;
};
</script>

<template>
    <Head title="Calendar" />
    <AdminLayout>
        <template #header>
            <h1 class="text-xl font-bold text-surface-900">Calendar</h1>
        </template>

        <!-- Date Navigation -->
        <div class="card p-4 mb-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center space-x-3">
                    <button @click="prevDay" class="p-2 rounded-lg hover:bg-surface-100 transition-colors">
                        <svg class="w-5 h-5 text-surface-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <h2 class="text-lg font-semibold text-surface-900">{{ formatDateDisplay(selectedDate) }}</h2>
                    <button @click="nextDay" class="p-2 rounded-lg hover:bg-surface-100 transition-colors">
                        <svg class="w-5 h-5 text-surface-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
                <div class="flex items-center space-x-3">
                    <button @click="goToday" class="text-sm text-brand-600 hover:text-brand-700 font-medium">Today</button>
                    <input v-model="selectedDate" type="date" class="input-field text-sm w-auto" />
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="flex items-center space-x-4 mb-4 text-xs text-surface-500">
            <span class="flex items-center space-x-1.5"><span class="w-3 h-3 rounded bg-brand-50 border border-brand-200"></span><span>Available</span></span>
            <span class="flex items-center space-x-1.5"><span class="w-3 h-3 rounded bg-red-100 border border-red-200"></span><span>Booked</span></span>
        </div>

        <!-- Calendar Grid -->
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px]">
                    <thead class="bg-surface-50 border-b border-surface-200">
                        <tr>
                            <th class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider px-4 py-3 w-20">Time</th>
                            <th
                                v-for="court in courts"
                                :key="court.id"
                                class="text-center text-xs font-semibold text-surface-500 uppercase tracking-wider px-4 py-3"
                            >
                                {{ court.name }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        <tr v-for="row in schedule" :key="row.time" class="hover:bg-surface-50/50 transition-colors">
                            <td class="px-4 py-2 text-sm font-medium text-surface-700 whitespace-nowrap">
                                {{ row.time }} - {{ row.end }}
                            </td>
                            <td
                                v-for="cell in row.courts"
                                :key="cell.court_id"
                                class="px-2 py-2 text-center"
                            >
                                <div
                                    class="rounded-lg px-2 py-2 text-xs font-medium"
                                    :class="cell.available
                                        ? 'bg-brand-50 text-brand-700 border border-brand-200'
                                        : 'bg-red-50 text-red-700 border border-red-200'"
                                >
                                    <template v-if="cell.available">
                                        Available
                                    </template>
                                    <template v-else>
                                        <div class="font-semibold">Booked</div>
                                        <div class="text-[10px] mt-0.5 opacity-75">{{ cell.booking?.user_name }}</div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
