<script setup>
defineProps({
    slots: {
        type: Array,
        required: true,
    },
    selectedSlot: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['select']);

const selectSlot = (slot) => {
    if (!slot.available) return;
    emit('select', slot);
};

const isSelected = (slot) => {
    return props.selectedSlot && props.selectedSlot.start === slot.start;
};
</script>

<script>
export default {
    // Access props in template without setup sugar issues
};
</script>

<template>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
        <button
            v-for="slot in slots"
            :key="slot.start"
            @click="selectSlot(slot)"
            :disabled="!slot.available"
            class="px-3 py-3 rounded-lg text-sm font-medium transition-all duration-200 border-2"
            :class="{
                'border-brand-600 bg-brand-600 text-white shadow-md': selectedSlot?.start === slot.start,
                'border-surface-200 bg-white text-surface-700 hover:border-brand-400 hover:bg-brand-50 cursor-pointer': slot.available && selectedSlot?.start !== slot.start,
                'border-surface-100 bg-surface-50 text-surface-300 cursor-not-allowed line-through': !slot.available,
            }"
        >
            <div class="text-center">
                <span class="block">{{ slot.start }} - {{ slot.end }}</span>
                <span
                    v-if="!slot.available"
                    class="text-xs mt-0.5 block text-red-400"
                >Booked</span>
                <span
                    v-else-if="selectedSlot?.start === slot.start"
                    class="text-xs mt-0.5 block text-brand-200"
                >Selected</span>
            </div>
        </button>
    </div>
</template>
