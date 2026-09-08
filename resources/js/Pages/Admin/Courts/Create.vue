<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    type: 'Indoor',
    price_per_hour: '',
    description: '',
    image: null,
    is_active: true,
});

const submit = () => {
    form.post(route('admin.courts.store'));
};
</script>

<template>
    <Head title="Create Court" />
    <AdminLayout>
        <template #header>
            <div class="flex items-center space-x-2">
                <Link :href="route('admin.courts.index')" class="text-surface-400 hover:text-surface-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </Link>
                <h1 class="text-xl font-bold text-surface-900">Create Court</h1>
            </div>
        </template>

        <div class="max-w-2xl">
            <div class="card p-6">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-surface-700 mb-1">Court Name</label>
                        <input v-model="form.name" type="text" class="input-field" placeholder="e.g. Court 5" />
                        <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-surface-700 mb-1">Type</label>
                        <select v-model="form.type" class="input-field">
                            <option value="Indoor">Indoor</option>
                            <option value="Outdoor">Outdoor</option>
                        </select>
                        <p v-if="form.errors.type" class="text-sm text-red-600 mt-1">{{ form.errors.type }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-surface-700 mb-1">Price per Hour (Rp)</label>
                        <input v-model="form.price_per_hour" type="number" class="input-field" placeholder="150000" min="0" />
                        <p v-if="form.errors.price_per_hour" class="text-sm text-red-600 mt-1">{{ form.errors.price_per_hour }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-surface-700 mb-1">Description</label>
                        <textarea v-model="form.description" rows="3" class="input-field" placeholder="Describe the court..."></textarea>
                        <p v-if="form.errors.description" class="text-sm text-red-600 mt-1">{{ form.errors.description }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-surface-700 mb-1">Court Image</label>
                        <input type="file" @input="form.image = $event.target.files[0]" accept="image/*" class="input-field" />
                        <p v-if="form.errors.image" class="text-sm text-red-600 mt-1">{{ form.errors.image }}</p>
                    </div>

                    <div class="flex items-center space-x-2">
                        <input v-model="form.is_active" type="checkbox" id="is_active" class="rounded border-surface-300 text-brand-600 focus:ring-brand-500" />
                        <label for="is_active" class="text-sm font-medium text-surface-700">Active</label>
                    </div>

                    <div class="flex items-center space-x-3 pt-4">
                        <button type="submit" :disabled="form.processing" class="btn-primary">
                            {{ form.processing ? 'Creating...' : 'Create Court' }}
                        </button>
                        <Link :href="route('admin.courts.index')" class="text-sm text-surface-500 hover:text-surface-700">Cancel</Link>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
