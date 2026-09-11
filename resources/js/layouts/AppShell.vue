<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppHeader from '@/components/layout/AppHeader.vue';
import AppSidebar from '@/components/layout/AppSidebar.vue';
import AppBottomNav from '@/components/layout/AppBottomNav.vue';
import LogoutConfirmDialog from '@/components/dialogs/LogoutConfirmDialog.vue';
import { useMobileNav } from '@/composables/useMobileNav';
import { useThemeMode } from '@/composables/useThemeMode';

defineProps<{
    title?: string;
}>();

const { isMobile } = useMobileNav();
const { isDark } = useThemeMode();
const isDrawerOpen = ref(true);

onMounted(() => {
    // Di layar ponsel, default drawer tertutup agar tidak menghalangi
    if (isMobile.value) {
        isDrawerOpen.value = false;
    }
});

const toggleDrawer = () => {
    isDrawerOpen.value = !isDrawerOpen.value;
};
</script>

<template>
    <v-app
        :theme="isDark ? 'dark' : 'light'"
        class="font-sans min-h-screen text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-950 transition-colors duration-200"
    >
        <!-- Sidebar Navigation Drawer -->
        <AppSidebar v-model="isDrawerOpen" />

        <!-- Header Top Bar -->
        <AppHeader :title="title" @toggle-drawer="toggleDrawer" />

        <!-- Area Konten Utama Terpadu Vuetify Main -->
        <v-main class="pb-16 lg:pb-6 transition-all duration-200">
            <div class="w-full max-w-[1600px] mx-auto p-4 sm:p-6 lg:p-8">
                <slot />
            </div>
        </v-main>

        <!-- Navigasi Bawah Khusus Layar Ponsel (Bottom Navigation) -->
        <AppBottomNav />

        <!-- Dialog Alert Prompt Konfirmasi Logout Global -->
        <LogoutConfirmDialog />
    </v-app>
</template>
