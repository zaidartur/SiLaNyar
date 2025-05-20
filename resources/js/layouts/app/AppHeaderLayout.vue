<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppHeader from '@/components/AppHeader.vue';
import AppNavbar from '@/components/AppNavbar.vue';
import AppShell from '@/components/AppShell.vue';
</script>

<template>
    <AppShell class="flex-col">
        <div class="fixed top-0 left-0 right-0 z-50">
            <AppHeader />
            <AppNavbar />
        </div>
        <div class="mt-[header-height]"> <!-- Adjust this value based on your header + navbar height -->
            <AppContent>
                <slot />
            </AppContent>
        </div>
    </AppShell>
</template>

<style scoped>
/* Replace [header-height] with actual combined height of header + navbar */
.mt-[header-height] {
    margin-top: 120px; /* Adjust this value as needed */
}
</style>