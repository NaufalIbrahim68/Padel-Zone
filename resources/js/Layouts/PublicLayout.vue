<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import FlashMessage from '@/Components/FlashMessage.vue';

const page = usePage();
const auth = computed(() => page.props.auth);
const isMenuOpen = ref(false);

const navLinks = [
    { name: 'Home', href: 'home' },
    { name: 'Courts', href: 'courts.index' },
    { name: 'Pricing', href: 'pricing' },
    { name: 'Book a Court', href: 'booking', auth: true },
    { name: 'Contact', href: 'contact' },
];

const isActive = (routeName) => {
    try {
        return route().current(routeName);
    } catch {
        return false;
    }
};
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-surface-900 sticky top-0 z-50 border-b border-surface-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <!-- Logo -->
                    <Link :href="route('home')" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                            </svg>
                        </div>
                        <span class="text-white font-display font-bold text-xl tracking-tight">PADELZONE</span>
                    </Link>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-1">
                        <template v-for="link in navLinks" :key="link.name">
                            <Link
                                v-if="!link.auth || auth.user"
                                :href="route(link.href)"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200"
                                :class="isActive(link.href)
                                    ? 'text-brand-400 bg-surface-800'
                                    : 'text-surface-300 hover:text-white hover:bg-surface-800'"
                            >
                                {{ link.name }}
                            </Link>
                        </template>
                    </div>

                    <!-- Auth Section -->
                    <div class="hidden md:flex items-center space-x-3">
                        <template v-if="auth.user">
                            <Link
                                v-if="auth.user.role === 'admin'"
                                :href="route('admin.dashboard')"
                                class="text-sm text-yellow-400 hover:text-yellow-300 font-medium transition-colors"
                            >
                                Admin Panel
                            </Link>
                            <Link
                                :href="route('my-bookings')"
                                class="text-sm text-surface-300 hover:text-white font-medium transition-colors"
                            >
                                My Bookings
                            </Link>
                            <div class="relative group">
                                <button class="flex items-center space-x-2 text-surface-300 hover:text-white transition-colors">
                                    <div class="w-8 h-8 bg-brand-600 rounded-full flex items-center justify-center text-sm font-semibold text-white">
                                        {{ auth.user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <span class="text-sm font-medium">{{ auth.user.name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-surface-800 rounded-lg shadow-xl border border-surface-700 py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                    <Link :href="auth.user.role === 'admin' ? route('admin.dashboard') : route('dashboard')" class="block px-4 py-2 text-sm text-surface-300 hover:text-white hover:bg-surface-700">
                                        {{ auth.user.role === 'admin' ? 'Admin Panel' : 'Dashboard' }}
                                    </Link>
                                    <Link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-surface-300 hover:text-white hover:bg-surface-700">
                                        Profile
                                    </Link>
                                    <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-4 py-2 text-sm text-surface-300 hover:text-white hover:bg-surface-700">
                                        Log Out
                                    </Link>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <Link :href="route('login')" class="text-sm text-surface-300 hover:text-white font-medium transition-colors">
                                Login
                            </Link>
                            <Link :href="route('register')" class="btn-primary btn-sm">
                                Register
                            </Link>
                        </template>
                    </div>

                    <!-- Mobile menu button -->
                    <button @click="isMenuOpen = !isMenuOpen" class="md:hidden text-surface-300 hover:text-white">
                        <svg v-if="!isMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div v-show="isMenuOpen" class="md:hidden border-t border-surface-800">
                <div class="px-4 py-3 space-y-1">
                    <template v-for="link in navLinks" :key="link.name">
                        <Link
                            v-if="!link.auth || auth.user"
                            :href="route(link.href)"
                            class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            :class="isActive(link.href) ? 'text-brand-400 bg-surface-800' : 'text-surface-300 hover:text-white hover:bg-surface-800'"
                            @click="isMenuOpen = false"
                        >
                            {{ link.name }}
                        </Link>
                    </template>
                    <template v-if="auth.user">
                        <Link
                            v-if="auth.user.role === 'admin'"
                            :href="route('admin.dashboard')"
                            class="block px-3 py-2 text-sm text-yellow-400 font-semibold hover:bg-surface-800 rounded-lg"
                            @click="isMenuOpen = false"
                        >
                            Admin Panel
                        </Link>
                        <Link
                            v-else
                            :href="route('dashboard')"
                            class="block px-3 py-2 text-sm text-surface-300 hover:text-white hover:bg-surface-800 rounded-lg"
                            @click="isMenuOpen = false"
                        >
                            Dashboard
                        </Link>
                        <Link :href="route('my-bookings')" class="block px-3 py-2 text-sm text-surface-300 hover:text-white hover:bg-surface-800 rounded-lg" @click="isMenuOpen = false">
                            My Bookings
                        </Link>
                        <Link :href="route('profile.edit')" class="block px-3 py-2 text-sm text-surface-300 hover:text-white hover:bg-surface-800 rounded-lg" @click="isMenuOpen = false">
                            Profile
                        </Link>
                        <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-3 py-2 text-sm text-surface-300 hover:text-white hover:bg-surface-800 rounded-lg" @click="isMenuOpen = false">
                            Log Out
                        </Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')" class="block px-3 py-2 text-sm text-surface-300 hover:text-white hover:bg-surface-800 rounded-lg" @click="isMenuOpen = false">
                            Login
                        </Link>
                        <Link :href="route('register')" class="block px-3 py-2 text-sm text-brand-400 hover:text-brand-300 hover:bg-surface-800 rounded-lg" @click="isMenuOpen = false">
                            Register
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        <FlashMessage />

        <!-- Page Content -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-surface-900 text-surface-400 border-t border-surface-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Brand -->
                    <div class="md:col-span-1">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                                </svg>
                            </div>
                            <span class="text-white font-display font-bold text-xl">PADELZONE</span>
                        </div>
                        <p class="text-sm leading-relaxed">
                            Premium padel experience in the heart of the city. State-of-the-art courts, professional facilities, and a vibrant community.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-2 text-sm">
                            <li><Link :href="route('home')" class="hover:text-brand-400 transition-colors">Home</Link></li>
                            <li><Link :href="route('courts.index')" class="hover:text-brand-400 transition-colors">Courts</Link></li>
                            <li><Link :href="route('pricing')" class="hover:text-brand-400 transition-colors">Pricing</Link></li>
                            <li><Link :href="route('contact')" class="hover:text-brand-400 transition-colors">Contact</Link></li>
                        </ul>
                    </div>

                    <!-- Hours -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Operating Hours</h4>
                        <ul class="space-y-2 text-sm">
                            <li>Monday - Friday: 06:00 - 23:00</li>
                            <li>Saturday: 06:00 - 23:00</li>
                            <li>Sunday: 06:00 - 23:00</li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Contact Us</h4>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Jl. Sudirman No. 123, Jakarta</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span>+62 21 1234 5678</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span>info@padelzone.id</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-surface-800 mt-8 pt-8 text-center text-sm">
                    <p>&copy; {{ new Date().getFullYear() }} PADELZONE. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
