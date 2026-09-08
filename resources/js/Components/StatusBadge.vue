<script setup>
defineProps({
    status: {
        type: String,
        required: true,
    },
});

const statusClasses = {
    pending: 'bg-yellow-100 text-yellow-800',
    confirmed: 'bg-blue-100 text-blue-800',
    cancelled: 'bg-red-100 text-red-800',
    completed: 'bg-green-100 text-green-800',
};

const statusLabels = {
    pending: 'Pending',
    confirmed: 'Confirmed',
    cancelled: 'Cancelled',
    completed: 'Completed',
};
</script>

<template>
    <span
        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
        :class="statusClasses[status] || 'bg-gray-100 text-gray-800'"
    >
        {{ statusLabels[status] || status }}
    </span>
</template>
