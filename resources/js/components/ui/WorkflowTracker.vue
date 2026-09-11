<script setup lang="ts">
import { computed } from 'vue';

export interface WorkflowStep {
    id: string | number;
    title: string;
    description: string;
    status: 'completed' | 'current' | 'pending' | 'rejected';
    icon: string;
}

const props = withDefaults(
    defineProps<{
        steps?: WorkflowStep[];
        currentStepIndex?: number;
    }>(),
    {
        steps: () => [
            {
                id: 1,
                title: 'Pendaftaran Sampel',
                description: 'Verifikasi berkas permohonan',
                status: 'completed',
                icon: 'mdi-clipboard-check-outline',
            },
            {
                id: 2,
                title: 'Pembayaran Retribusi',
                description: 'Verifikasi bukti setor',
                status: 'completed',
                icon: 'mdi-cash-check',
            },
            {
                id: 3,
                title: 'Pengambilan Contoh Uji',
                description: 'Penjemputan PPCU atau loket lab',
                status: 'current',
                icon: 'mdi-test-tube',
            },
            {
                id: 4,
                title: 'Pengujian Laboratorium',
                description: 'Analisis parameter oleh analis',
                status: 'pending',
                icon: 'mdi-microscope',
            },
            {
                id: 5,
                title: 'Review & TTE LHU',
                description: 'Penyelia, Kepala Lab, dan Kadis',
                status: 'pending',
                icon: 'mdi-certificate-outline',
            },
        ],
        currentStepIndex: 2,
    }
);

const resolvedSteps = computed(() => {
    return props.steps.map((step, idx) => {
        let status = step.status;
        if (props.currentStepIndex !== undefined) {
            if (idx < props.currentStepIndex) status = 'completed';
            else if (idx === props.currentStepIndex) status = 'current';
            else status = 'pending';
        }
        return {
            ...step,
            status,
        };
    });
});
</script>

<template>
    <div class="w-full bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 border border-slate-200/80 dark:border-slate-800">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                    <v-icon color="primary" size="20">mdi-timeline-text-outline</v-icon>
                    Alur Layanan Pengujian Laboratorium
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Prosedur operasional standar DLH Kabupaten Karanganyar
                </p>
            </div>
            <div class="hidden sm:block">
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                    5 Tahapan Pengujian
                </span>
            </div>
        </div>

        <!-- Desktop Flow (Grid 5 Kolom) -->
        <div class="hidden lg:grid grid-cols-5 gap-3 relative">
            <div
                v-for="(step, index) in resolvedSteps"
                :key="step.id"
                class="flex flex-col items-center text-center relative"
            >
                <!-- Connecting Line -->
                <div
                    v-if="index < resolvedSteps.length - 1"
                    class="absolute top-5 left-1/2 w-full h-0.5 -z-0"
                    :class="step.status === 'completed' ? 'bg-emerald-600 dark:bg-emerald-500' : 'bg-slate-200 dark:bg-slate-800'"
                />

                <!-- Step Icon / Indicator -->
                <div
                    class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm z-10 transition shadow-sm"
                    :class="[
                        step.status === 'completed'
                            ? 'bg-emerald-600 text-white dark:bg-emerald-500'
                            : step.status === 'current'
                              ? 'bg-amber-500 text-white ring-4 ring-amber-100 dark:ring-amber-950/70'
                              : 'bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500 border border-slate-200 dark:border-slate-700',
                    ]"
                >
                    <v-icon v-if="step.status === 'completed'" size="20">mdi-check</v-icon>
                    <v-icon v-else size="18">{{ step.icon }}</v-icon>
                </div>

                <!-- Step Info -->
                <div class="mt-3 px-1">
                    <p
                        class="text-xs font-bold leading-tight"
                        :class="
                            step.status === 'current'
                                ? 'text-amber-600 dark:text-amber-400'
                                : step.status === 'completed'
                                  ? 'text-slate-800 dark:text-slate-200'
                                  : 'text-slate-400 dark:text-slate-500'
                        "
                    >
                        {{ step.title }}
                    </p>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 leading-snug">
                        {{ step.description }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Mobile & Tablet Flow (Vertical Timeline) -->
        <div class="lg:hidden space-y-4">
            <div
                v-for="(step, index) in resolvedSteps"
                :key="step.id"
                class="flex items-start gap-3.5 relative"
            >
                <!-- Vertical Line -->
                <div
                    v-if="index < resolvedSteps.length - 1"
                    class="absolute top-8 left-4 w-0.5 h-[calc(100%+0.5rem)] -z-0"
                    :class="step.status === 'completed' ? 'bg-emerald-600 dark:bg-emerald-500' : 'bg-slate-200 dark:bg-slate-800'"
                />

                <!-- Step Indicator -->
                <div
                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 z-10 shadow-sm"
                    :class="[
                        step.status === 'completed'
                            ? 'bg-emerald-600 text-white dark:bg-emerald-500'
                            : step.status === 'current'
                              ? 'bg-amber-500 text-white ring-4 ring-amber-100 dark:ring-amber-950/70'
                              : 'bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500 border border-slate-200 dark:border-slate-700',
                    ]"
                >
                    <v-icon v-if="step.status === 'completed'" size="16">mdi-check</v-icon>
                    <v-icon v-else size="16">{{ step.icon }}</v-icon>
                </div>

                <!-- Details -->
                <div class="pt-1 flex-1">
                    <div class="flex items-center justify-between">
                        <span
                            class="text-xs font-bold"
                            :class="
                                step.status === 'current'
                                    ? 'text-amber-600 dark:text-amber-400'
                                    : step.status === 'completed'
                                      ? 'text-slate-800 dark:text-slate-200'
                                      : 'text-slate-400 dark:text-slate-500'
                            "
                        >
                            {{ step.title }}
                        </span>
                        <span
                            class="text-[10px] uppercase font-semibold px-2 py-0.5 rounded-full"
                            :class="[
                                step.status === 'completed'
                                    ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400'
                                    : step.status === 'current'
                                      ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400'
                                      : 'bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500',
                            ]"
                        >
                            {{ step.status === 'completed' ? 'Selesai' : step.status === 'current' ? 'Berjalan' : 'Antrian' }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        {{ step.description }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
