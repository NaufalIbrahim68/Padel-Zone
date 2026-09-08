<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import BookingCalendar from '@/Components/BookingCalendar.vue';
import TimeSlotGrid from '@/Components/TimeSlotGrid.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    courts: Array,
});

const selectedDate = ref('');
const selectedCourt = ref(null);
const selectedSlot = ref(null);
const slots = ref([]);
const loadingSlots = ref(false);
const errors = ref({});

const formatPrice = (price) => 'Rp' + Number(price).toLocaleString('id-ID');

const form = useForm({
    court_id: null,
    booking_date: '',
    start_time: '',
    end_time: '',
    notes: '',
});

// Step tracking
const currentStep = computed(() => {
    if (!selectedDate.value) return 1;
    if (!selectedCourt.value) return 2;
    if (!selectedSlot.value) return 3;
    return 4;
});

// Fetch availability when date and court change
const fetchAvailability = async () => {
    if (!selectedDate.value || !selectedCourt.value) return;

    loadingSlots.value = true;
    selectedSlot.value = null;
    errors.value = {};

    try {
        const response = await axios.get(route('availability', selectedCourt.value.id), {
            params: { date: selectedDate.value },
        });
        slots.value = response.data.slots;
    } catch (err) {
        console.error('Failed to fetch availability:', err);
        slots.value = [];
    } finally {
        loadingSlots.value = false;
    }
};

watch(selectedDate, () => {
    selectedSlot.value = null;
    if (selectedCourt.value) fetchAvailability();
});

const selectCourt = (court) => {
    selectedCourt.value = court;
    selectedSlot.value = null;
    fetchAvailability();
};

const selectSlot = (slot) => {
    selectedSlot.value = slot;
};

const confirmBooking = () => {
    form.court_id = selectedCourt.value.id;
    form.booking_date = selectedDate.value;
    form.start_time = selectedSlot.value.start;
    form.end_time = selectedSlot.value.end;

    form.post(route('bookings.store'), {
        onError: (errs) => {
            errors.value = errs;
            // If time slot error, refresh availability
            if (errs.start_time) {
                fetchAvailability();
            }
        },
    });
};

const formatDateDisplay = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr + 'T00:00:00');
    return date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
};
</script>

<template>
    <Head title="Book a Court" />
    <PublicLayout>
        <section class="py-12 bg-surface-50 min-h-screen">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h1 class="section-title">Book a Court</h1>
                    <p class="section-subtitle">Select your date, court, and time slot to make a reservation.</p>
                </div>

                <!-- Progress Steps -->
                <div class="flex items-center justify-center mb-10">
                    <div v-for="(step, idx) in ['Date', 'Court', 'Time', 'Confirm']" :key="step" class="flex items-center">
                        <div class="flex items-center space-x-2">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold transition-colors"
                                :class="currentStep > idx + 1 ? 'bg-brand-600 text-white' : currentStep === idx + 1 ? 'bg-brand-600 text-white' : 'bg-surface-200 text-surface-500'"
                            >
                                <svg v-if="currentStep > idx + 1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                <span v-else>{{ idx + 1 }}</span>
                            </div>
                            <span class="text-sm font-medium hidden sm:inline" :class="currentStep >= idx + 1 ? 'text-surface-900' : 'text-surface-400'">{{ step }}</span>
                        </div>
                        <div v-if="idx < 3" class="w-8 sm:w-16 h-0.5 mx-2" :class="currentStep > idx + 1 ? 'bg-brand-600' : 'bg-surface-200'"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left: Selection Area -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Step 1: Date Selection -->
                        <div class="card p-6">
                            <h2 class="text-lg font-semibold text-surface-900 mb-4 flex items-center space-x-2">
                                <span class="w-6 h-6 bg-brand-600 text-white rounded-full text-xs flex items-center justify-center font-bold">1</span>
                                <span>Select Date</span>
                            </h2>
                            <BookingCalendar v-model="selectedDate" />
                        </div>

                        <!-- Step 2: Court Selection -->
                        <div v-if="selectedDate" class="card p-6">
                            <h2 class="text-lg font-semibold text-surface-900 mb-4 flex items-center space-x-2">
                                <span class="w-6 h-6 bg-brand-600 text-white rounded-full text-xs flex items-center justify-center font-bold">2</span>
                                <span>Select Court</span>
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <button
                                    v-for="court in courts"
                                    :key="court.id"
                                    @click="selectCourt(court)"
                                    class="p-3 rounded-xl border-2 text-left transition-all duration-200 flex items-center space-x-3 group"
                                    :class="selectedCourt?.id === court.id
                                        ? 'border-brand-600 bg-brand-50/50 shadow-sm'
                                        : 'border-surface-200 hover:border-brand-300 bg-white'"
                                >
                                    <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-surface-100">
                                        <img
                                            v-if="court.image_url || court.image"
                                            :src="court.image_url || ('/storage/' + court.image)"
                                            :alt="court.name"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                                            @error="$event.target.src = '/images/' + court.image"
                                        />
                                        <div v-else class="w-full h-full bg-surface-200 flex items-center justify-center">
                                            <span class="text-xs font-bold text-surface-500">PADEL</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-semibold text-surface-900 truncate">{{ court.name }}</h3>
                                            <span class="text-[10px] uppercase font-bold px-1.5 py-0.5 rounded" :class="court.type === 'Indoor' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700'">
                                                {{ court.type }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-surface-500 truncate mt-0.5">{{ court.description }}</p>
                                        <p class="text-sm font-bold text-brand-600 mt-1">{{ formatPrice(court.price_per_hour) }} <span class="text-[10px] font-normal text-surface-400">/hr</span></p>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Time Slot Selection -->
                        <div v-if="selectedCourt" class="card p-6">
                            <h2 class="text-lg font-semibold text-surface-900 mb-4 flex items-center space-x-2">
                                <span class="w-6 h-6 bg-brand-600 text-white rounded-full text-xs flex items-center justify-center font-bold">3</span>
                                <span>Select Time Slot</span>
                            </h2>

                            <div v-if="loadingSlots" class="text-center py-8">
                                <div class="inline-block w-8 h-8 border-4 border-brand-200 border-t-brand-600 rounded-full animate-spin"></div>
                                <p class="text-sm text-surface-500 mt-2">Loading availability...</p>
                            </div>

                            <div v-else-if="slots.length">
                                <div class="flex items-center space-x-4 mb-4 text-xs text-surface-500">
                                    <span class="flex items-center space-x-1"><span class="w-3 h-3 rounded bg-white border-2 border-surface-200"></span><span>Available</span></span>
                                    <span class="flex items-center space-x-1"><span class="w-3 h-3 rounded bg-surface-100 border-2 border-surface-100"></span><span>Booked</span></span>
                                    <span class="flex items-center space-x-1"><span class="w-3 h-3 rounded bg-brand-600"></span><span>Selected</span></span>
                                </div>
                                <TimeSlotGrid :slots="slots" :selected-slot="selectedSlot" @select="selectSlot" />
                            </div>

                            <!-- Errors -->
                            <div v-if="errors.start_time" class="mt-3 text-sm text-red-600 bg-red-50 p-3 rounded-lg">
                                {{ errors.start_time }}
                            </div>
                        </div>
                    </div>

                    <!-- Right: Booking Summary -->
                    <div>
                        <div class="card p-6 sticky top-24">
                            <h2 class="text-lg font-semibold text-surface-900 mb-4">Booking Summary</h2>

                            <div v-if="selectedDate || selectedCourt || selectedSlot" class="space-y-3">
                                <div v-if="selectedDate" class="flex justify-between text-sm">
                                    <span class="text-surface-500">Date</span>
                                    <span class="font-medium text-surface-900">{{ formatDateDisplay(selectedDate) }}</span>
                                </div>
                                <div v-if="selectedCourt" class="flex justify-between text-sm">
                                    <span class="text-surface-500">Court</span>
                                    <span class="font-medium text-surface-900">{{ selectedCourt.name }} ({{ selectedCourt.type }})</span>
                                </div>
                                <div v-if="selectedSlot" class="flex justify-between text-sm">
                                    <span class="text-surface-500">Time</span>
                                    <span class="font-medium text-surface-900">{{ selectedSlot.start }} - {{ selectedSlot.end }}</span>
                                </div>
                                <div v-if="selectedSlot" class="flex justify-between text-sm">
                                    <span class="text-surface-500">Duration</span>
                                    <span class="font-medium text-surface-900">1 hour</span>
                                </div>

                                <div v-if="selectedCourt" class="border-t border-surface-100 pt-3 mt-3">
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-surface-900">Total</span>
                                        <span class="text-xl font-bold text-brand-600">{{ formatPrice(selectedCourt.price_per_hour) }}</span>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div v-if="selectedSlot" class="pt-3">
                                    <label class="block text-sm font-medium text-surface-700 mb-1">Notes (optional)</label>
                                    <textarea v-model="form.notes" rows="2" class="input-field text-sm" placeholder="Any special requests?"></textarea>
                                </div>

                                <!-- Errors display -->
                                <div v-if="Object.keys(errors).length && !errors.start_time" class="text-sm text-red-600 bg-red-50 p-3 rounded-lg">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
                                    </ul>
                                </div>

                                <button
                                    v-if="selectedSlot"
                                    @click="confirmBooking"
                                    :disabled="form.processing"
                                    class="btn-primary w-full mt-4"
                                >
                                    <span v-if="form.processing" class="flex items-center justify-center space-x-2">
                                        <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                                        <span>Processing...</span>
                                    </span>
                                    <span v-else>Confirm Booking</span>
                                </button>
                            </div>
                            <div v-else class="text-center py-8">
                                <svg class="w-12 h-12 text-surface-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm text-surface-400">Select a date to begin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
