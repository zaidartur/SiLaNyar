<script setup lang="ts">
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { useMobileNav } from '@/composables/useMobileNav';

const { hasBottomNav, bottomNavItems } = useMobileNav();

const currentPath = computed(() => {
    if (typeof window !== 'undefined') {
        return window.location.pathname;
    }
    return '';
});

const isTabActive = (href: string) => {
    if (!href) return false;
    return currentPath.value === href || (href !== '/' && currentPath.value.startsWith(href));
};
</script>

<template>
    <v-bottom-navigation
        v-if="hasBottomNav && bottomNavItems.length > 0"
        grow
        elevation="8"
        color="primary"
        class="lg:hidden border-t border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur z-50 fixed bottom-0 left-0 right-0"
    >
        <v-btn
            v-for="item in bottomNavItems"
            :key="item.value"
            :value="item.value"
            :active="isTabActive(item.href)"
            class="transition py-1"
            @click.prevent="router.visit(item.href)"
        >
            <v-icon size="22">{{ item.icon }}</v-icon>
            <span class="text-[11px] font-semibold tracking-tight mt-0.5">{{ item.label }}</span>
        </v-btn>
    </v-bottom-navigation>
</template>
