<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <nav v-if="links.last_page > 1" class="flex items-center justify-between">
        <p class="text-sm text-surface-500">
            Showing <span class="font-medium">{{ links.from }}</span> to <span class="font-medium">{{ links.to }}</span> of <span class="font-medium">{{ links.total }}</span> results
        </p>
        <div class="flex space-x-1">
            <template v-for="link in links.links" :key="link.label">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    class="px-3 py-1.5 text-sm rounded-lg transition-colors"
                    :class="link.active
                        ? 'bg-brand-600 text-white font-semibold'
                        : 'text-surface-600 hover:bg-surface-100'"
                    v-html="link.label"
                    preserve-scroll
                />
                <span
                    v-else
                    class="px-3 py-1.5 text-sm text-surface-300"
                    v-html="link.label"
                />
            </template>
        </div>
    </nav>
</template>
