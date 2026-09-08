<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import FlashMessage from '@/Components/FlashMessage.vue';

const page = usePage();
const auth = computed(() => page.props.auth);
const isSidebarOpen = ref(false);

const navItems = [
    { name: 'Dashboard', href: 'admin.dashboard', icon: 'dashboard' },
    { name: 'Bookings', href: 'admin.bookings.index', icon: 'bookings' },
    { name: 'Calendar', href: 'admin.calendar', icon: 'calendar' },
    { name: 'Courts', href: 'admin.courts.index', icon: 'courts' },
];

const isActive = (routeName) => {
    try {
        return route().current(routeName) || route().current(routeName + '.*');
    } catch {
        return false;
    }
};
</script>

<template>
    <div class="min-h-screen bg-surface-100 flex">
        <!-- Sidebar: Sticky & fixed height to viewport so it never scrolls with page -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-surface-900 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen lg:flex-shrink-0"
            :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex flex-col h-full">
                <!-- Logo & Brand Header -->
                <div class="flex items-center justify-between h-16 px-6 border-b border-surface-800 flex-shrink-0">
                    <Link :href="route('admin.dashboard')" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                            </svg>
                        </div>
                        <span class="text-white font-display font-bold text-lg">PADELZONE</span>
                    </Link>
                    <button @click="isSidebarOpen = false" class="lg:hidden text-surface-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Primary Navigation (All in one unified list - No scrolling needed) -->
                <nav class="flex-1 px-3 py-2 space-y-1 overflow-y-auto">
                    <!-- Main Admin Views -->
                    <Link
                        v-for="item in navItems"
                        :key="item.name"
                        :href="route(item.href)"
                        class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200"
                        :class="isActive(item.href)
                            ? 'bg-brand-600 text-white font-semibold shadow-sm'
                            : 'text-surface-400 hover:text-white hover:bg-surface-800'"
                    >
                        <!-- Dashboard icon -->
                        <svg v-if="item.icon === 'dashboard'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <!-- Bookings icon -->
                        <svg v-if="item.icon === 'bookings'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <!-- Calendar icon -->
                        <svg v-if="item.icon === 'calendar'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <!-- Courts icon -->
                        <svg v-if="item.icon === 'courts'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>{{ item.name }}</span>
                    </Link>

                    <!-- Section Divider: Unified package with nav items -->
                    <div class="pt-3 pb-1">
                        <div class="border-t border-surface-800"></div>
                    </div>

                    <!-- Back to Site -->
                    <Link
                        :href="route('home')"
                        class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium text-surface-400 hover:text-white hover:bg-surface-800 transition-colors duration-200"
                    >
                        <svg class="w-5 h-5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Back to Site</span>
                    </Link>

                    <!-- Logout -->
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium text-surface-400 hover:text-red-400 hover:bg-surface-800 transition-colors duration-200 w-full text-left"
                    >
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Log Out</span>
                    </Link>
                </nav>

                <!-- Compact Admin User Profile Card at Sidebar Bottom -->
                <div class="border-t border-surface-800 p-3 flex-shrink-0 bg-surface-950/40">
                    <div class="flex items-center space-x-3 px-2 py-1">
                        <div class="w-8 h-8 bg-brand-600 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0 shadow-sm">
                            {{ auth.user?.name?.charAt(0)?.toUpperCase() }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-white truncate leading-tight">{{ auth.user?.name }}</p>
                            <p class="text-[10px] text-surface-400 truncate mt-0.5">{{ auth.user?.email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Sidebar overlay (mobile) -->
        <div
            v-if="isSidebarOpen"
            @click="isSidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/50 lg:hidden"
        ></div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top bar -->
            <header class="bg-white border-b border-surface-200 h-16 flex items-center px-4 lg:px-8 sticky top-0 z-30 shadow-sm">
                <button @click="isSidebarOpen = true" class="lg:hidden mr-4 text-surface-500 hover:text-surface-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="flex-1">
                    <slot name="header" />
                </div>
                <div class="flex items-center space-x-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-surface-700">{{ auth.user?.name }}</p>
                        <p class="text-xs text-surface-500">Administrator</p>
                    </div>
                    <div class="w-9 h-9 bg-brand-600 rounded-full flex items-center justify-center text-sm font-semibold text-white">
                        {{ auth.user?.name?.charAt(0)?.toUpperCase() }}
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <FlashMessage />

            <!-- Page content -->
            <main class="flex-1 p-4 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
