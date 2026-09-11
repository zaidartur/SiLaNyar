<script setup lang="ts">
defineProps<{
    label: string;
    value: number | string;
    icon: string;
    color?: string;
    description?: string;
    trendText?: string;
    trendPositive?: boolean;
}>();
</script>

<template>
    <v-card
        elevation="1"
        rounded="xl"
        class="p-5 border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 transition hover:shadow-md hover:border-emerald-300 dark:hover:border-emerald-700"
    >
        <div class="flex items-start justify-between">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    {{ label }}
                </span>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                    {{ value }}
                </div>
            </div>

            <div
                class="w-12 h-12 rounded-xl flex items-center justify-center transition shrink-0"
                :class="[color || 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400']"
            >
                <v-icon size="26">{{ icon }}</v-icon>
            </div>
        </div>

        <div v-if="description || trendText" class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
            <span class="text-slate-500 dark:text-slate-400 truncate">{{ description }}</span>
            <span
                v-if="trendText"
                class="font-semibold px-2 py-0.5 rounded-md text-[11px]"
                :class="trendPositive ? 'text-emerald-700 bg-emerald-50 dark:bg-emerald-950/50' : 'text-slate-600 bg-slate-100 dark:bg-slate-800'"
            >
                {{ trendText }}
            </span>
        </div>
    </v-card>
</template>
