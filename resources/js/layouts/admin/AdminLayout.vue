<script setup lang="ts">
import AppShell from '@/layouts/AppShell.vue';

defineProps<{
    title?: string;
}>();
</script>

<template>
    <AppShell :title="title">
        <slot />
    </AppShell>
</template>