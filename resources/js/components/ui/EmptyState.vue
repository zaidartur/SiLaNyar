<script setup lang="ts">
interface Props {
    title: string;
    description: string;
    icon?: string;
    actionLabel?: string;
    actionHref?: string;
}

withDefaults(defineProps<Props>(), {
    icon: 'mdi-folder-open-outline',
    actionLabel: undefined,
    actionHref: undefined,
});

const emit = defineEmits<{
    action: [];
}>();

function handleAction() {
    emit('action');
}
</script>

<template>
    <div
        class="flex flex-col items-center justify-center p-8 sm:p-12 text-center rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40"
    >
        <div
            class="w-16 h-16 rounded-2xl flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 mb-4"
        >
            <v-icon size="32">{{ icon }}</v-icon>
        </div>

        <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-slate-200 mb-1">
            {{ title }}
        </h3>

        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-sm mb-6">
            {{ description }}
        </p>

        <slot name="action">
            <template v-if="actionLabel">
                <v-btn
                    v-if="actionHref"
                    :href="actionHref"
                    color="primary"
                    variant="elevated"
                    elevation="1"
                    rounded="lg"
                    class="text-none font-semibold px-5"
                >
                    {{ actionLabel }}
                </v-btn>
                <v-btn
                    v-else
                    color="primary"
                    variant="elevated"
                    elevation="1"
                    rounded="lg"
                    class="text-none font-semibold px-5"
                    @click="handleAction"
                >
                    {{ actionLabel }}
                </v-btn>
            </template>
        </slot>
    </div>
</template>
