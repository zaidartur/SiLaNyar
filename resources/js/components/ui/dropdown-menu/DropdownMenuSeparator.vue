<script setup lang="ts">
/* eslint-disable */
import { cn } from '@/lib/utils';
import { DropdownMenuSeparator, type DropdownMenuSeparatorProps } from 'radix-vue';
import { computed, type HTMLAttributes } from 'vue';

const props = defineProps<
    DropdownMenuSeparatorProps & {
        class?: HTMLAttributes['class'];
    }
>();

const delegatedProps = computed(() => {
    const { class: _, ...delegated } = props;

    return delegated;
});
</script>

<template>
    <DropdownMenuSeparator v-bind="delegatedProps"  />
</template>
