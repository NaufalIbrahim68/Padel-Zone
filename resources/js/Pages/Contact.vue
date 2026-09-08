<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Contact" />
    <PublicLayout>
        <section class="py-16 bg-surface-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <p class="text-brand-600 font-semibold text-sm tracking-widest uppercase mb-2">Get in Touch</p>
                    <h1 class="section-title">Contact Us</h1>
                    <p class="section-subtitle">Have questions? We'd love to hear from you.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Contact Info -->
                    <div>
                        <h2 class="text-xl font-bold text-surface-900 mb-6">Visit PADELZONE</h2>
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-surface-900">Address</h3>
                                    <p class="text-surface-500 text-sm">Jl. Jenderal Sudirman No. 123<br>Jakarta Selatan 12190<br>Indonesia</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-surface-900">Phone</h3>
                                    <p class="text-surface-500 text-sm">+62 21 1234 5678</p>
                                    <p class="text-surface-500 text-sm">+62 812 3456 7890 (WhatsApp)</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-surface-900">Email</h3>
                                    <p class="text-surface-500 text-sm">info@padelzone.id</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-surface-900">Hours</h3>
                                    <p class="text-surface-500 text-sm">Every day: 06:00 - 23:00</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form (decorative) -->
                    <div class="card p-8">
                        <h2 class="text-xl font-bold text-surface-900 mb-6">Send us a Message</h2>
                        <form @submit.prevent class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-surface-700 mb-1">Full Name</label>
                                <input type="text" class="input-field" placeholder="Your name">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-surface-700 mb-1">Email</label>
                                <input type="email" class="input-field" placeholder="your@email.com">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-surface-700 mb-1">Subject</label>
                                <input type="text" class="input-field" placeholder="How can we help?">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-surface-700 mb-1">Message</label>
                                <textarea rows="4" class="input-field" placeholder="Your message..."></textarea>
                            </div>
                            <button type="submit" class="btn-primary w-full">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
