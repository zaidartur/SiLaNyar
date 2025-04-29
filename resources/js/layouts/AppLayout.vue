<script setup lang="ts">
import AppLayout from '@/layouts/app/AppHeaderLayout.vue';
import AppFooter from '../components/AppFooter.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>
.
<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
        <AppFooter />
    </AppLayout>
</template>
