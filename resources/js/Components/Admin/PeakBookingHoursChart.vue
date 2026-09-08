<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import Chart from 'chart.js/auto';

const props = defineProps({
    data: {
        type: Array,
        required: true,
        default: () => [],
    },
    period: {
        type: String,
        default: '7d',
    },
});

const canvasRef = ref(null);
let chartInstance = null;

const periodOptions = [
    { label: 'Last 7 Days', value: '7d' },
    { label: 'Last 30 Days', value: '30d' },
    { label: 'This Month', value: 'month' },
];

const selectPeriod = (value) => {
    if (value === props.period) return;

    router.get(
        route('admin.dashboard'),
        { peak_period: value },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['peakBookingHours', 'peakPeriod'],
        }
    );
};

const initChart = () => {
    if (!canvasRef.value) return;

    if (chartInstance) {
        chartInstance.destroy();
        chartInstance = null;
    }

    const ctx = canvasRef.value.getContext('2d');
    const labels = props.data.map((d) => d.hour);
    const counts = props.data.map((d) => d.bookings);

    // Dynamic bar colors: highlight busier hours with richer emerald
    const maxCount = Math.max(...counts, 1);
    const backgroundColors = counts.map((count) => {
        if (count === 0) return 'rgba(16, 185, 129, 0.15)';
        const opacity = 0.45 + (count / maxCount) * 0.55;
        return `rgba(16, 185, 129, ${opacity.toFixed(2)})`;
    });

    chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Bookings',
                    data: counts,
                    backgroundColor: backgroundColors,
                    hoverBackgroundColor: '#059669',
                    borderRadius: 5,
                    borderSkipped: false,
                    maxBarThickness: 24,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: '#18181b',
                    titleColor: '#f4f4f5',
                    bodyColor: '#10b981',
                    borderColor: '#27272a',
                    borderWidth: 1,
                    padding: 10,
                    boxPadding: 4,
                    displayColors: false,
                    titleFont: {
                        size: 12,
                        weight: '600',
                    },
                    bodyFont: {
                        size: 13,
                        weight: 'bold',
                    },
                    callbacks: {
                        title: (context) => {
                            const index = context[0]?.dataIndex;
                            const item = props.data[index];
                            return item ? `Time: ${item.hour}` : '';
                        },
                        label: (context) => {
                            const value = context.parsed.y;
                            return `${value} ${value === 1 ? 'Booking' : 'Bookings'}`;
                        },
                    },
                },
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        color: '#71717a',
                        font: {
                            size: 10,
                        },
                        maxRotation: 45,
                        autoSkip: true,
                        maxTicksLimit: 12,
                    },
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)',
                        drawBorder: false,
                    },
                    ticks: {
                        color: '#71717a',
                        font: {
                            size: 11,
                        },
                        precision: 0,
                        stepSize: 1,
                    },
                },
            },
        },
    });
};

watch(
    () => props.data,
    () => {
        initChart();
    },
    { deep: true }
);

onMounted(() => {
    initChart();
});

onUnmounted(() => {
    if (chartInstance) {
        chartInstance.destroy();
        chartInstance = null;
    }
});
</script>

<template>
    <div class="card p-6 flex flex-col justify-between h-full">
        <!-- Header & Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h2 class="text-base font-bold text-surface-900 tracking-tight">Peak Booking Hours</h2>
                </div>
                <p class="text-xs text-surface-500 mt-0.5">Booking distribution by hour (06:00 – 23:00)</p>
            </div>

            <!-- Period Filter -->
            <div class="inline-flex bg-surface-100 p-1 rounded-xl border border-surface-200 self-start sm:self-auto">
                <button
                    v-for="opt in periodOptions"
                    :key="opt.value"
                    @click="selectPeriod(opt.value)"
                    class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all"
                    :class="period === opt.value
                        ? 'bg-white text-surface-900 shadow-sm border border-surface-200/60'
                        : 'text-surface-500 hover:text-surface-800'"
                >
                    {{ opt.label }}
                </button>
            </div>
        </div>

        <!-- Canvas Container -->
        <div class="relative w-full h-64">
            <canvas ref="canvasRef"></canvas>
        </div>
    </div>
</template>
