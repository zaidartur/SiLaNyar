<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppShell from '@/layouts/AppShell.vue';
import StatKpiCard from '@/components/ui/StatKpiCard.vue';
import WorkflowTracker from '@/components/ui/WorkflowTracker.vue';

defineProps<{
    statistik: {
        perluVerifikasi: number;
        totalSelesai: number;
    };
    hasil_uji?: any[];
}>();
</script>

<template>
    <Head title="Dashboard Kepala Laboratorium" />

    <AppShell>
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Kepala Laboratorium
                        </span>
                        <span class="text-xs text-slate-400 dark:text-slate-500">
                            Verifikasi Teknis & TTE Tahap 1
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Verifikasi Hasil Pengujian Laboratorium
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Pemeriksaan teknis berkas Lhus, verifikasi hasil uji analis (persetujuan atau revisi), dan Tanda Tangan Elektronik tahap awal.
                    </p>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <v-btn
                        component="a"
                        href="/pegawai/hasiluji"
                        color="primary"
                        variant="elevated"
                        elevation="1"
                        rounded="lg"
                        prepend-icon="mdi-file-certificate-outline"
                        class="text-none font-semibold text-xs"
                    >
                        Daftar Lembar Hasil Uji
                    </v-btn>
                </div>
            </div>

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <StatKpiCard
                    label="Menunggu Verifikasi"
                    :value="statistik.perluVerifikasi"
                    icon="mdi-file-document-edit-outline"
                    color="bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400"
                    description="Draf Lhus siap diverifikasi & TTE Tahap 1"
                />

                <StatKpiCard
                    label="Lhus Tervalidasi"
                    :value="statistik.totalSelesai"
                    icon="mdi-draw-pen"
                    color="bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400"
                    description="Telah disahkan dan diteruskan ke Kepala Dinas"
                />

                <div class="bg-gradient-to-br from-emerald-900 to-slate-900 text-white rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-200">
                                Otoritas Penjaminan Mutu
                            </span>
                            <v-icon size="20" color="white">mdi-shield-check</v-icon>
                        </div>
                        <p class="text-xs text-emerald-100/90 mt-2 leading-relaxed">
                            Kepala Laboratorium memegang tanggung jawab penuh atas validitas metodologi dan keabsahan hasil analisis laboratorium.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-emerald-700/50 flex items-center justify-between text-xs text-emerald-200">
                        <span>Sesuai Akreditasi KAN</span>
                        <v-icon size="16">mdi-certificate</v-icon>
                    </div>
                </div>
            </div>

            <!-- Workflow Tracker -->
            <WorkflowTracker :current-step-index="4" />

            <!-- Panduan Verifikasi -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm space-y-4">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <v-icon color="primary" size="20">mdi-clipboard-check-outline</v-icon>
                        Ketentuan Verifikasi & TTE Kepala Laboratorium
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Prosedur pengesahan Lembar Hasil Uji Sementara (Lhus)
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs text-slate-600 dark:text-slate-300">
                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block">1. Akurasi & Baku Mutu</span>
                        Memeriksa ketepatan kalkulasi data ukur dan kesesuaian nilai ambang batas baku mutu lingkungan pada draf Lhus.
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block">2. Catatan Revisi</span>
                        Jika data meragukan, dokumen dikembalikan ke status revisi dengan catatan perbaikan spesifik bagi analis.
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block">3. TTE Tahap 1</span>
                        Setelah hasil tervalidasi, Kepala Lab membubuhkan tanda tangan elektronik dan berkas diteruskan ke Kepala Dinas.
                    </div>
                </div>
            </div>
        </div>
    </AppShell>
</template>
