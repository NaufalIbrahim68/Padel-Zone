<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({ courts: Array });

const formatPrice = (price) => 'Rp' + Number(price).toLocaleString('id-ID');

const toggleActive = (court) => {
    router.patch(route('admin.courts.toggle', court.id));
};
</script>

<template>
    <Head title="Manage Courts" />
    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-bold text-surface-900">Courts</h1>
                <Link :href="route('admin.courts.create')" class="btn-primary btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Add Court
                </Link>
            </div>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div v-for="court in courts" :key="court.id" class="card overflow-hidden">
                <div class="h-44 bg-surface-900 flex items-center justify-center relative overflow-hidden">
                    <img
                        v-if="court.image_url || court.image"
                        :src="court.image_url || ('/storage/' + court.image)"
                        :alt="court.name"
                        class="w-full h-full object-cover"
                        @error="$event.target.src = '/images/' + court.image"
                    />
                    <svg v-else class="w-16 h-16 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                    </svg>
                    <div class="absolute top-3 right-3">
                        <span
                            class="text-xs font-semibold px-2.5 py-1 rounded-full"
                            :class="court.is_active ? 'bg-green-500 text-white' : 'bg-red-500 text-white'"
                        >
                            {{ court.is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="text-lg font-bold text-surface-900">{{ court.name }}</h3>
                            <p class="text-xs text-surface-400">{{ court.type }} • {{ court.bookings_count || 0 }} bookings</p>
                        </div>
                        <p class="text-lg font-bold text-brand-600">{{ formatPrice(court.price_per_hour) }}</p>
                    </div>
                    <p class="text-sm text-surface-500 mb-4 line-clamp-2">{{ court.description }}</p>
                    <div class="flex items-center space-x-2">
                        <Link :href="route('admin.courts.edit', court.id)" class="btn-primary btn-sm flex-1 text-center text-xs">Edit</Link>
                        <button
                            @click="toggleActive(court)"
                            class="btn-sm flex-1 text-center text-xs font-medium rounded-lg py-1.5 px-3 transition-colors"
                            :class="court.is_active
                                ? 'bg-red-50 text-red-600 hover:bg-red-100'
                                : 'bg-green-50 text-green-600 hover:bg-green-100'"
                        >
                            {{ court.is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
