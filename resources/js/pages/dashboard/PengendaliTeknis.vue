<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppShell from '@/layouts/AppShell.vue';
import StatKpiCard from '@/components/ui/StatKpiCard.vue';
import WorkflowTracker from '@/components/ui/WorkflowTracker.vue';

defineProps<{
    statistik: {
        pengajuan: number;
        jadwal: number;
        pengujian: number;
        hasil_uji: number;
    };
    pengajuan?: any[];
}>();
</script>

<template>
    <Head title="Dashboard Pengendali Teknis" />

    <AppShell>
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Pengendali Teknis
                        </span>
                        <span class="text-xs text-slate-400 dark:text-slate-500">
                            Kendali Mutu & Evaluasi Teknis
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Monitoring & Disposisi Teknis Laboratorium
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Kendali mutu permohonan, penugasan PPCU, evaluasi kesesuaian contoh uji, dan penyusunan draf Lhus.
                    </p>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <v-btn
                        component="a"
                        href="/pegawai/pengambilan"
                        color="primary"
                        variant="elevated"
                        elevation="1"
                        rounded="lg"
                        prepend-icon="mdi-truck-delivery-outline"
                        class="text-none font-semibold text-xs"
                    >
                        Jadwal PPCU
                    </v-btn>
                    <v-btn
                        component="a"
                        href="/pegawai/hasiluji"
                        variant="outlined"
                        color="primary"
                        rounded="lg"
                        prepend-icon="mdi-file-document-edit-outline"
                        class="text-none font-semibold text-xs"
                    >
                        Draf Lhus
                    </v-btn>
                </div>
            </div>

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <StatKpiCard
                    label="Permohonan Masuk"
                    :value="statistik.pengajuan"
                    icon="mdi-clipboard-text-outline"
                    color="bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400"
                    description="Kajian kelayakan teknis awal"
                />

                <StatKpiCard
                    label="Penugasan PPCU"
                    :value="statistik.jadwal"
                    icon="mdi-map-marker-path"
                    color="bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400"
                    description="Jadwal sampling contoh uji lapangan"
                />

                <StatKpiCard
                    label="Pengujian Lab"
                    :value="statistik.pengujian"
                    icon="mdi-flask-outline"
                    color="bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400"
                    description="Dalam proses analisis laboratorium"
                />

                <StatKpiCard
                    label="Draf Lhus / Hasil"
                    :value="statistik.hasil_uji"
                    icon="mdi-file-certificate-outline"
                    color="bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-400"
                    description="Lembar hasil uji sementara tersusun"
                />
            </div>

            <!-- Workflow Tracker -->
            <WorkflowTracker :current-step-index="2" />

            <!-- Tanggung Jawab Operasional -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm space-y-4">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <v-icon color="primary" size="20">mdi-shield-check-outline</v-icon>
                        Tanggung Jawab Operasional Pengendali Teknis
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Standar Operasional Prosedur Pengujian DLH Kabupaten Karanganyar
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs text-slate-600 dark:text-slate-300">
                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block">1. Disposisi Jadwal PPCU</span>
                        Menerima permohonan sampling dan menetapkan jadwal tugas petugas pengambil contoh uji ke titik lokasi pemohon.
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block">2. Evaluasi Sampel di Loket</span>
                        Memverifikasi kesesuaian contoh uji di loket laboratorium (sampel mandiri dievaluasi Sesuai atau Tidak Sesuai; sampel PPCU otomatis terjamin).
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block">3. Alokasi Pengujian ke Penyelia</span>
                        Mengalokasikan contoh uji yang telah memenuhi kriteria kepada Penyelia untuk diteruskan kepada analis yang kompeten.
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block">4. Penyusunan Draf Lhus</span>
                        Menyusun draf Lembar Hasil Uji Sementara (Lhus) berdasarkan rekapan hasil uji sebelum diverifikasi Kepala Laboratorium.
                    </div>
                </div>
            </div>
        </div>
    </AppShell>
</template>
