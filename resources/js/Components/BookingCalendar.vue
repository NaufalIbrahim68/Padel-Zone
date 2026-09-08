<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    modelValue: String,
    minDate: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(['update:modelValue']);

const today = new Date();
today.setHours(0, 0, 0, 0);

const currentMonth = ref(today.getMonth());
const currentYear = ref(today.getFullYear());

const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

const daysInMonth = computed(() => {
    return new Date(currentYear.value, currentMonth.value + 1, 0).getDate();
});

const firstDayOfMonth = computed(() => {
    return new Date(currentYear.value, currentMonth.value, 1).getDay();
});

const calendarDays = computed(() => {
    const days = [];
    // Empty cells before first day
    for (let i = 0; i < firstDayOfMonth.value; i++) {
        days.push(null);
    }
    // Actual days
    for (let d = 1; d <= daysInMonth.value; d++) {
        days.push(d);
    }
    return days;
});

const formatDate = (day) => {
    const m = String(currentMonth.value + 1).padStart(2, '0');
    const d = String(day).padStart(2, '0');
    return `${currentYear.value}-${m}-${d}`;
};

const isSelected = (day) => {
    return props.modelValue === formatDate(day);
};

const isToday = (day) => {
    return day === today.getDate()
        && currentMonth.value === today.getMonth()
        && currentYear.value === today.getFullYear();
};

const isPast = (day) => {
    const date = new Date(currentYear.value, currentMonth.value, day);
    date.setHours(0, 0, 0, 0);
    return date < today;
};

const selectDay = (day) => {
    if (!day || isPast(day)) return;
    emit('update:modelValue', formatDate(day));
};

const prevMonth = () => {
    if (currentMonth.value === 0) {
        currentMonth.value = 11;
        currentYear.value--;
    } else {
        currentMonth.value--;
    }
};

const nextMonth = () => {
    if (currentMonth.value === 11) {
        currentMonth.value = 0;
        currentYear.value++;
    } else {
        currentMonth.value++;
    }
};

const canGoPrev = computed(() => {
    return currentYear.value > today.getFullYear() ||
        (currentYear.value === today.getFullYear() && currentMonth.value > today.getMonth());
});
</script>

<template>
    <div class="bg-white rounded-xl border border-surface-200 p-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <button
                @click="prevMonth"
                :disabled="!canGoPrev"
                class="p-1.5 rounded-lg hover:bg-surface-100 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
            >
                <svg class="w-5 h-5 text-surface-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <h3 class="text-sm font-semibold text-surface-900">
                {{ monthNames[currentMonth] }} {{ currentYear }}
            </h3>
            <button @click="nextMonth" class="p-1.5 rounded-lg hover:bg-surface-100 transition-colors">
                <svg class="w-5 h-5 text-surface-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <!-- Day names -->
        <div class="grid grid-cols-7 mb-2">
            <div v-for="day in dayNames" :key="day" class="text-center text-xs font-medium text-surface-500 py-1">
                {{ day }}
            </div>
        </div>

        <!-- Calendar grid -->
        <div class="grid grid-cols-7 gap-1">
            <div v-for="(day, idx) in calendarDays" :key="idx" class="aspect-square">
                <button
                    v-if="day"
                    @click="selectDay(day)"
                    :disabled="isPast(day)"
                    class="w-full h-full flex items-center justify-center text-sm rounded-lg transition-all duration-150"
                    :class="{
                        'bg-brand-600 text-white font-semibold shadow-sm': isSelected(day),
                        'ring-2 ring-brand-400 text-brand-600 font-semibold': isToday(day) && !isSelected(day),
                        'text-surface-400 cursor-not-allowed': isPast(day),
                        'text-surface-700 hover:bg-brand-50 hover:text-brand-600 cursor-pointer': !isPast(day) && !isSelected(day),
                    }"
                >
                    {{ day }}
                </button>
            </div>
        </div>
    </div>
</template>
