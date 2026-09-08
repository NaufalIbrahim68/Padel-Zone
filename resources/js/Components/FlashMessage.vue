<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage();
const show = ref(false);
const message = ref('');
const type = ref('success');

const flash = computed(() => page.props.flash);

watch(flash, (val) => {
    if (val?.success) {
        message.value = val.success;
        type.value = 'success';
        show.value = true;
    } else if (val?.error) {
        message.value = val.error;
        type.value = 'error';
        show.value = true;
    }

    if (show.value) {
        setTimeout(() => { show.value = false; }, 4000);
    }
}, { immediate: true });
</script>

<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="translate-y-[-100%] opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-[-100%] opacity-0"
    >
        <div v-if="show" class="fixed top-4 right-4 z-[100] max-w-sm">
            <div
                class="rounded-lg shadow-lg px-4 py-3 flex items-center space-x-3"
                :class="type === 'success' ? 'bg-brand-600 text-white' : 'bg-red-600 text-white'"
            >
                <svg v-if="type === 'success'" class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <svg v-else class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">{{ message }}</p>
                <button @click="show = false" class="ml-auto text-white/80 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </Transition>
</template>
