<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppShell from '@/layouts/AppShell.vue';
import StatKpiCard from '@/components/ui/StatKpiCard.vue';
import WorkflowTracker from '@/components/ui/WorkflowTracker.vue';

defineProps<{
    statistik: {
        totalPengujian: number;
        dalamProses: number;
    };
    pengujian?: any[];
}>();
</script>

<template>
    <Head title="Dashboard Penyelia Laboratorium" />

    <AppShell>
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-purple-100 text-purple-800 dark:bg-purple-950/80 dark:text-purple-300">
                            Penyelia Laboratorium
                        </span>
                        <span class="text-xs text-slate-400 dark:text-slate-500">
                            Supervisi Analisis & Pembagian Parameter
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Supervisi & Pengawasan Pengujian
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Pengawasan alur analisis pengujian, distribusi parameter sampel ke Analis, dan kendali baku mutu uji laboratorium.
                    </p>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <v-btn
                        component="a"
                        href="/pegawai/pengujian"
                        color="primary"
                        variant="elevated"
                        elevation="1"
                        rounded="lg"
                        prepend-icon="mdi-flask-outline"
                        class="text-none font-semibold text-xs"
                    >
                        Alokasi Uji Lab
                    </v-btn>
                </div>
            </div>

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <StatKpiCard
                    label="Pengujian Terdistribusi"
                    :value="statistik.totalPengujian"
                    icon="mdi-format-list-checks"
                    color="bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-400"
                    description="Total sampel dalam daftar analisis pengujian"
                />

                <StatKpiCard
                    label="Dalam Proses Analisis"
                    :value="statistik.dalamProses"
                    icon="mdi-progress-clock"
                    color="bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400"
                    description="Sedang dianalisis oleh analis laboratorium"
                />

                <div class="bg-gradient-to-br from-purple-800 to-slate-900 text-white rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-purple-200">
                                Integritas Pengujian
                            </span>
                            <v-icon size="20" color="white">mdi-check-all</v-icon>
                        </div>
                        <p class="text-xs text-purple-100/90 mt-2 leading-relaxed">
                            Pastikan pembagian parameter uji merata dan verifikasi data mentah analis sebelum diajukan ke Pengendali Teknis.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-purple-700/50 flex items-center justify-between text-xs text-purple-200">
                        <span>Kontrol Akurasi & Presisi</span>
                        <v-icon size="16">mdi-shield-check</v-icon>
                    </div>
                </div>
            </div>

            <!-- Workflow Tracker -->
            <WorkflowTracker :current-step-index="3" />

            <!-- Tanggung Jawab Penyelia -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm space-y-4">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <v-icon color="primary" size="20">mdi-clipboard-account-outline</v-icon>
                        Tugas Pokok & Kendali Penyelia
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Fungsi pengawasan teknis operasional harian laboratorium
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs text-slate-600 dark:text-slate-300">
                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block">1. Penerimaan Sampel Sesuai</span>
                        Menerima sampel yang telah dinyatakan sesuai dari Pengendali Teknis beserta parameter yang dimohonkan.
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block">2. Distribusi Beban Analis</span>
                        Membagi beban kerja analisis parameter baku mutu kepada masing-masing analis laboratorium sesuai bidang keahlian.
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block">3. Evaluasi Hasil Ukur</span>
                        Memeriksa keabsahan lembar kerja analisis analis sebelum draf lembar hasil uji disusun lebih lanjut.
                    </div>
                </div>
            </div>
        </div>
    </AppShell>
</template>
