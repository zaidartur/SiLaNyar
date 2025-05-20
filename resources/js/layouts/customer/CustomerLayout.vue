<script setup lang="ts">
import AppSidebarCustomer from '@/components/AppSidebarCustomer.vue';
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
    <AppSidebarCustomer :breadcrumbs="breadcrumbs">
        <slot />
    </AppSidebarCustomer>
</template>
