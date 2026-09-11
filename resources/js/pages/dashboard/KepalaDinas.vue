<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppShell from '@/layouts/AppShell.vue';
import StatKpiCard from '@/components/ui/StatKpiCard.vue';
import WorkflowTracker from '@/components/ui/WorkflowTracker.vue';

defineProps<{
    statistik: {
        perluTTE: number;
        totalSelesai: number;
        totalPengajuan: number;
    };
    hasil_uji?: any[];
}>();
</script>

<template>
    <Head title="Dashboard Kepala Dinas Lingkungan Hidup" />

    <AppShell>
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Kepala Dinas Lingkungan Hidup
                        </span>
                        <span class="text-xs text-slate-400 dark:text-slate-500">
                            Pengesahan Akhir & TTE Tahap 2
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Otoritas Pengesahan Laporan Hasil Uji (LHU)
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Pengesahan tertinggi pelayanan laboratorium, pembubuhan TTE Final, dan pengawasan realisasi retribusi daerah.
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
                        prepend-icon="mdi-draw"
                        class="text-none font-semibold text-xs"
                    >
                        Pengesahan LHU (TTE)
                    </v-btn>
                    <v-btn
                        component="a"
                        href="/pegawai/laporan-keuangan"
                        variant="outlined"
                        color="primary"
                        rounded="lg"
                        prepend-icon="mdi-chart-areaspline"
                        class="text-none font-semibold text-xs"
                    >
                        Laporan Retribusi
                    </v-btn>
                </div>
            </div>

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <StatKpiCard
                    label="Menunggu TTE Kadis"
                    :value="statistik.perluTTE"
                    icon="mdi-draw-pen"
                    color="bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400"
                    description="Lhus telah lolos verifikasi Kepala Lab"
                />

                <StatKpiCard
                    label="Total LHU Resmi Terbit"
                    :value="statistik.totalSelesai"
                    icon="mdi-certificate"
                    color="bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400"
                    description="Dokumen sah dan siap diunduh pemohon"
                />

                <StatKpiCard
                    label="Total Pelayanan Uji"
                    :value="statistik.totalPengajuan"
                    icon="mdi-office-building-outline"
                    color="bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-400"
                    description="Akumulasi permohonan pengujian DLH"
                />
            </div>

            <!-- Workflow Tracker -->
            <WorkflowTracker :current-step-index="4" />

            <!-- Kebijakan Pengesahan -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm space-y-4">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <v-icon color="primary" size="20">mdi-shield-crown-outline</v-icon>
                        Alur Pengesahan Akhir Laporan Hasil Uji (LHU)
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Tata kelola penerbitan sertifikat hasil uji terstandarisasi
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs text-slate-600 dark:text-slate-300">
                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block">1. Pembubuhan TTE Final</span>
                        Kepala Dinas membubuhkan tanda tangan elektronik tersertifikasi (TTE Tahap 2 / Final) pada dokumen yang telah disetujui Kepala Laboratorium.
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block">2. Penerbitan Sertifikat Resmi</span>
                        Setelah ditandatangani, dokumen resmi berubah status menjadi LHU sah yang dilengkapi kode verifikasi QR-Code keabsahan.
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block">3. Distribusi Pemohon</span>
                        Pemohon dapat mengunduh dokumen LHU asli setelah menyelesaikan survei Indeks Kepuasan Masyarakat (IKM) pelayanan publik.
                    </div>
                </div>
            </div>
        </div>
    </AppShell>
</template>
