<script setup lang="ts">
import AppShell from '@/layouts/AppShell.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    title?: string;
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    title: undefined,
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell :title="title">
        <slot />
    </AppShell>
</template>
