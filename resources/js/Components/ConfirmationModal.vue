<script setup>
import { ref } from 'vue';

const props = defineProps({
    show: Boolean,
    title: {
        type: String,
        default: 'Confirm Action',
    },
    message: {
        type: String,
        default: 'Are you sure you want to proceed?',
    },
    confirmText: {
        type: String,
        default: 'Confirm',
    },
    cancelText: {
        type: String,
        default: 'Cancel',
    },
    variant: {
        type: String,
        default: 'danger', // 'danger' | 'primary'
    },
});

const emit = defineEmits(['confirm', 'cancel']);
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="fixed inset-0 z-[200] flex items-center justify-center p-4">
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black/50" @click="emit('cancel')"></div>

                <!-- Modal -->
                <div class="relative bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
                    <h3 class="text-lg font-semibold text-surface-900">{{ title }}</h3>
                    <p class="mt-2 text-sm text-surface-600">{{ message }}</p>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button
                            @click="emit('cancel')"
                            class="px-4 py-2 text-sm font-medium text-surface-700 bg-surface-100 hover:bg-surface-200 rounded-lg transition-colors"
                        >
                            {{ cancelText }}
                        </button>
                        <button
                            @click="emit('confirm')"
                            class="px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors"
                            :class="variant === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-brand-600 hover:bg-brand-700'"
                        >
                            {{ confirmText }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
